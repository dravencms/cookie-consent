<?php declare(strict_types = 1);

namespace Dravencms\AdminModule\CookieConsentModule;


use Dravencms\AdminModule\Components\CookieConsent\SettingsForm\SettingsFormFactory;
use Dravencms\AdminModule\Components\CookieConsent\SettingsForm\SettingsForm;
use Dravencms\AdminModule\Components\CookieConsent\SettingsGrid\SettingsGridFactory;
use Dravencms\AdminModule\Components\CookieConsent\SettingsGrid\SettingsGrid;
use Dravencms\AdminModule\SecuredPresenter;
use Dravencms\Flash;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Repository\SettingsRepository;

/**
 * Description of SettingsPresenter
 *
 * @author Adam Schubert
 */
class SettingsPresenter extends SecuredPresenter
{
    /** @var SettingsRepository @inject */
    public $settingsRepository;

    /** @var SettingsGridFactory @inject */
    public $settingsGridFactory;

    /** @var SettingsFormFactory @inject */
    public $settingsFormFactory;

    /** @var null|Settings */
    private $settings = null;

    public function renderDefault(): void
    {
        $this->template->h1 = $this->translator->translate('cookieConsent.cookieConsent');
    }

    /**
     * @isAllowed(cookieConsent,edit)
     * @param $id
     * @throws \Nette\Application\BadRequestException
     */
    public function actionEdit(int $id = null): void
    {
        if ($id) {
            $settings = $this->settingsRepository->getOneById($id);

            if (!$settings) {
                $this->error();
            }
            $this->settings = $settings;
            $this->template->h1 = $this->translator->translate('cookieConsent.editCookieConsentIdentifier', ['identifier' => $settings->getIdentifier()]);
        } else {
            $this->template->h1 = $this->translator->translate('cookieConsent.newCookieConsent');
        }
    }

    /**
     * @return SettingsForm
     */
    protected function createComponentFormSettings(): SettingsForm
    {
        $control = $this->settingsFormFactory->create($this->settings);
        $control->onSuccess[] = function()
        {
            $this->flashMessage($this->translator->translate('cookieConsent.cookieConsentHasBeenSuccessfullySaved'), Flash::SUCCESS);
            $this->redirect('Settings:');
        };
        return $control;
    }

    /**
     * @return SettingsGrid
     */
    public function createComponentGridSettings(): SettingsGrid
    {
        $control = $this->settingsGridFactory->create();
        $control->onDelete[] = function()
        {
            $this->flashMessage($this->translator->translate('cookieConsent.cookieConsentHasBeenSuccessfullyDeleted'), Flash::SUCCESS);
            $this->redirect('Settings:');
        };
        return $control;
    }
}
