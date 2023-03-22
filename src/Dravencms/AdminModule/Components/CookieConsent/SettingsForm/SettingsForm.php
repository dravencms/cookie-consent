<?php declare(strict_types = 1);
/*
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace Dravencms\AdminModule\Components\CookieConsent\SettingsForm;

use Dravencms\Components\BaseControl\BaseControl;
use Dravencms\Components\BaseForm\BaseFormFactory;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Entities\SettingsTranslation;
use Dravencms\Model\CookieConsent\Repository\SettingsRepository;
use Dravencms\Model\CookieConsent\Repository\SettingsTranslationRepository;
use Dravencms\Locale\CurrentLocaleResolver;
use Dravencms\Model\Locale\Repository\LocaleRepository;
use Dravencms\Database\EntityManager;
use Dravencms\Components\BaseForm\Form;
use Nette\Security\User;

/**
 * Description of SettingsForm
 *
 * @author Adam Schubert <adam.schubert@sg1-game.net>
 */
class SettingsForm extends BaseControl
{
    /** @var BaseFormFactory */
    private $baseFormFactory;

    /** @var EntityManager */
    private $entityManager;

    /** @var SettingsRepository */
    private $settingsRepository;

    /** @var SettingsTranslationRepository */
    private $settingsTranslationRepository;

    /** @var LocaleRepository */
    private $localeRepository;

    /** @var \Dravencms\Model\Locale\Entities\Locale|null */
    private $currentLocale;

    /** @var User */
    private $user;

    /** @var Settings|null */
    private $settings = null;

    /** @var array */
    public $onSuccess = [];

    /**
     * SettingsForm constructor.
     * @param BaseFormFactory $baseFormFactory
     * @param EntityManager $entityManager
     * @param SettingsRepository $settingsRepository
     * @param SettingsTranslationRepository $settingsTranslationRepository
     * @param LocaleRepository $localeRepository
     * @param CurrentLocaleResolver $currentLocaleResolver
     * @param User $user
     * @param Settings|null $settings
     * @throws \Exception
     */
    public function __construct(
        BaseFormFactory $baseFormFactory,
        EntityManager $entityManager,
        SettingsRepository $settingsRepository,
        SettingsTranslationRepository $settingsTranslationRepository,
        LocaleRepository $localeRepository,
        CurrentLocaleResolver $currentLocaleResolver,
        User $user,
        Settings $settings = null
    ) {

        $this->settings = $settings;

        $this->baseFormFactory = $baseFormFactory;
        $this->entityManager = $entityManager;
        $this->settingsRepository = $settingsRepository;
        $this->user = $user;
        $this->settingsTranslationRepository = $settingsTranslationRepository;
        $this->currentLocale = $currentLocaleResolver->getCurrentLocale();
        $this->localeRepository = $localeRepository;


        if ($this->settings) {
            $defaults = [
                'identifier' => $this->settings->getIdentifier(),
                'isActive' => $this->settings->isActive(),
                'isAutoclearCookies' => $this->settings->isAutoclearCookies(),
                'cookieExpiration' => $this->settings->getCookieExpiration(),
                'isPageScripts' => $this->settings->isPageScripts(),
                'isForceConsent' => $this->settings->isForceConsent(),
                'mode' => $this->settings->getMode(),
                'cookieDomain' => $this->settings->getCookieDomain(),
            ];

            foreach ($this->settings->getTranslations() AS $translation)
            {
                $defaults[$translation->getLocale()->getLanguageCode()]['title'] = $translation->getTitle();
                $defaults[$translation->getLocale()->getLanguageCode()]['description'] = $translation->getDescription();
                $defaults[$translation->getLocale()->getLanguageCode()]['revisionMessage'] = $translation->getRevisionMessage();
                $defaults[$translation->getLocale()->getLanguageCode()]['personalDataProtectionUrl'] = $translation->getPersonalDataProtectionUrl();
                $defaults[$translation->getLocale()->getLanguageCode()]['cookiesInformationUrl'] = $translation->getCookiesInformationUrl();
            }
        }
        else{
            $defaults = [
                'isActive' => true,
                'isAutoclearCookies' => true,
                'cookieExpiration' => 365,
                'isPageScripts' => true,
                'isForceConsent' => false,
                'mode' => Settings::MODE_OPT_OUT,
            ];
        }

        $this['form']->setDefaults($defaults);
    }

    /**
     * @return Form
     */
    protected function createComponentForm(): Form
    {
        $form = $this->baseFormFactory->create();

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            $container = $form->addContainer($activeLocale->getLanguageCode());
            $container->addText('title')
                ->setRequired('cookieConset.pleaseEnterTitle')
                ->addRule(Form::MAX_LENGTH, 'cookieConset.titleIsTooLong', 255);

            $container->addTextArea('description')
            ->setRequired('cookieConset.pleaseEnterDescription');

            $container->addTextArea('revisionMessage')
            ->setRequired('cookieConset.pleaseEnterRevisionMessage');

            $container->addText('personalDataProtectionUrl')
            ->setRequired('cookieConset.pleaseEnterPersonalDataProtectionUrl');

            $container->addText('cookiesInformationUrl')
            ->setRequired('cookieConset.pleaseEnterCookiesInformationUrl');
        }

        $form->addText('identifier')
            ->setRequired('cookieConset.pleaseEnterIdentifier');

        $form->addInteger('cookieExpiration');
        $form->addText('cookieDomain');

        $modes = [
            Settings::MODE_OPT_OUT => 'Opt Out',
            Settings::MODE_OPT_IN => 'Opt In',
        ];
        $form->addSelect('mode', null, $modes)
            ->setRequired('cookieConset.pleaseSelectValidMode');

        $form->addCheckbox('isActive');
        $form->addCheckbox('isAutoclearCookies');
        $form->addCheckbox('isPageScripts');
        $form->addCheckbox('isForceConsent');


        $form->addSubmit('send');

        $form->onValidate[] = [$this, 'editFormValidate'];
        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editFormValidate(Form $form): void
    {
        $values = $form->getValues();

        if (!$this->settingsRepository->isIdentifierFree($values->identifier, $this->settings)) {
            $form->addError('cookieConset.thisIdentifierIsAlreadyUsed');
        }

        if (!$this->user->isAllowed('cookieConsent', 'edit')) {
            $form->addError('cookieConset.youHaveNoPermissionToEditThisCookieConsent');
        }
    }

    /**
     * @param Form $form
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editFormSucceeded(Form $form): void
    {
        $values = $form->getValues();
        
        $cookieDomain = ($values->cookieDomain ? $values->cookieDomain: null);

        if ($this->settings) {
            $settings = $this->settings;
            $settings->setIdentifier($values->identifier);
            $settings->setIsActive($values->isActive);
            $settings->setIsAutoclearCookies($values->isAutoclearCookies);
            $settings->setIsPageScripts($values->isPageScripts);
            $settings->setIsForceConsent($values->isForceConsent);
            $settings->setCookieExpiration($values->cookieExpiration);
            $settings->setCookieDomain($cookieDomain);
            $settings->setMode($values->mode);
        } else {
            $settings = new Settings(
                $values->identifier,
                $values->isActive,
                $values->isAutoclearCookies,
                $values->cookieExpiration,
                $values->isPageScripts,
                $values->isForceConsent,
                $values->mode,
                $cookieDomain
            );
        }

        $this->entityManager->persist($settings);

        $this->entityManager->flush();

        // isActive can be set only on one item
        $this->settingsRepository->processIsActive($settings);


        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            if ($settingsTranslation = $this->settingsTranslationRepository->getTranslation($settings, $activeLocale))
            {
                $settingsTranslation->setTitle($values->{$activeLocale->getLanguageCode()}->title);
                $settingsTranslation->setDescription($values->{$activeLocale->getLanguageCode()}->description);
                $settingsTranslation->setRevisionMessage($values->{$activeLocale->getLanguageCode()}->revisionMessage);
                $settingsTranslation->setPersonalDataProtectionUrl($values->{$activeLocale->getLanguageCode()}->personalDataProtectionUrl);
                $settingsTranslation->setCookiesInformationUrl($values->{$activeLocale->getLanguageCode()}->cookiesInformationUrl);
            }
            else
            {
                $settingsTranslation = new SettingsTranslation(
                    $settings,
                    $activeLocale,
                    $values->{$activeLocale->getLanguageCode()}->title,
                    $values->{$activeLocale->getLanguageCode()}->description,
                    $values->{$activeLocale->getLanguageCode()}->revisionMessage,
                    $values->{$activeLocale->getLanguageCode()}->personalDataProtectionUrl,
                    $values->{$activeLocale->getLanguageCode()}->cookiesInformationUrl,
                );
            }

            $this->entityManager->persist($settingsTranslation);
        }

        $this->entityManager->flush();

        $this->onSuccess();
    }

    public function render(): void
    {
        $template = $this->template;
        $template->activeLocales = $this->localeRepository->getActive();
        $template->setFile(__DIR__ . '/SettingsForm.latte');
        $template->render();
    }
}