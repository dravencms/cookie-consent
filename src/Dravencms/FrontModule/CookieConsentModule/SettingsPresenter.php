<?php declare(strict_types = 1);

namespace Dravencms\FrontModule\CookieConsentModule;

use Dravencms\Model\Locale\Entities\Locale;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Repository\SettingsRepository;
use Dravencms\CookieConsent\Translator;
use Nette\Localization\ITranslator;
use Dravencms\BasePresenter;

/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class SettingsPresenter extends BasePresenter
{
    /** @var ITranslator @inject */
    public $translator;

    /** @var SettingsRepository @inject */
    public $settingsRepository;

    public function renderDefault(): void
    {
        $settings = $this->settingsRepository->getOneByActive();
        $this->template->settings = $settings;
        $this->template->config = null;

        if (!$settings) {
            return;
        }

        $languages = [];
        foreach ($settings->getTranslations() as $settingsTranslation) {
            $translator = new Translator($settingsTranslation->getLocale(), $this->translator, 'cookieConsent');
            $languageCode = $settingsTranslation->getLocale()->getLanguageCode();
            $moreInformationLinks = [];

            if ($cookiesInformationUrl = $settingsTranslation->getCookiesInformationUrl()) {
                $moreInformationLinks[] = sprintf(
                    '<a href="%s" class="cc__link" target="_blank" rel="noopener noreferrer">%s</a>',
                    htmlspecialchars($cookiesInformationUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                    $translator->translate('settings_modal.blocks.b4.cookies_information')
                );
            }

            if ($personalDataProtectionUrl = $settingsTranslation->getPersonalDataProtectionUrl()) {
                $moreInformationLinks[] = sprintf(
                    '<a href="%s" class="cc__link" target="_blank" rel="noopener noreferrer">%s</a>',
                    htmlspecialchars($personalDataProtectionUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                    $translator->translate('settings_modal.blocks.b4.personal_data_protection')
                );
            }

            $languages[$languageCode] = [
                'consentModal' => [
                    'title' => $settingsTranslation->getTitle(),
                    'description' => $settingsTranslation->getDescription(),
                    'acceptAllBtn' => $translator->translate('consent_modal.primary_btn.text'),
                    'showPreferencesBtn' => $translator->translate('consent_modal.secondary_btn.text'),
                    'revisionMessage' => $settingsTranslation->getRevisionMessage(),
                ],
                'preferencesModal' => [
                    'title' => $translator->translate('settings_modal.title'),
                    'savePreferencesBtn' => $translator->translate('settings_modal.save_settings_btn'),
                    'acceptAllBtn' => $translator->translate('settings_modal.accept_all_btn'),
                    'acceptNecessaryBtn' => $translator->translate('settings_modal.reject_all_btn'),
                    'closeIconLabel' => $translator->translate('settings_modal.close_btn_label'),
                    'sections' => [
                        [
                            'title' => $translator->translate('settings_modal.blocks.b0.title'),
                            'description' => $translator->translate('settings_modal.blocks.b0.description')
                        ],
                        [
                            'title' => $translator->translate('settings_modal.blocks.b1.title'),
                            'description' => $translator->translate('settings_modal.blocks.b1.description'),
                            'linkedCategory' => 'necessary',
                        ],
                        [
                            'title' => $translator->translate('settings_modal.blocks.b2.title'),
                            'description' => $translator->translate('settings_modal.blocks.b2.description'),
                            'linkedCategory' => 'analytics',
                            'cookieTable' => $this->createCookieTable($translator, 'b2', [
                                ['^_ga', '_ga', '2 years'],
                                ['_gid', '_gid', '1 year'],
                                ['_gat', '_gat', '1 minute'],
                            ]),
                        ], [
                            'title' => $translator->translate('settings_modal.blocks.b3.title'),
                            'description' => $translator->translate('settings_modal.blocks.b3.description'),
                            'linkedCategory' => 'targeting',
                            'cookieTable' => $this->createCookieTable($translator, 'b3', [
                                ['_fbp', '_fbp', '3 months'],
                                ['CONSENT', 'CONSENT', '1 year'],
                                ['^__Secure\\.', '__Secure', '2 years'],
                            ]),
                        ], [
                            'title' => $translator->translate('settings_modal.blocks.b4.title'),
                            'description' => implode(' | ', $moreInformationLinks)
                        ]
                    ]
                ]
            ];

            if ($moreInformationLinks === []) {
                array_pop($languages[$languageCode]['preferencesModal']['sections']);
            }
        }

        if ($languages === []) {
            return;
        }

        $cookie = [
            'name' => 'cc_cookie',
            'expiresAfterDays' => $settings->getCookieExpiration(),
        ];
        if ($settings->getCookieDomain()) {
            $cookie['domain'] = $settings->getCookieDomain();
        }

        $optionalCategoriesEnabled = $settings->getMode() === Settings::MODE_OPT_OUT;

        $config = [
            'mode' => $settings->getMode(),
            'autoClearCookies' => $settings->isAutoclearCookies(),
            'manageScriptTags' => $settings->isPageScripts(),
            'disablePageInteraction' => $settings->isForceConsent(),
            'revision' => $settings->getId(),
            'cookie' => $cookie,
            'guiOptions' => [
                'consentModal' => [
                    'layout' => 'bar',
                    'position' => 'bottom',
                ],
                'preferencesModal' => [
                    'layout' => 'bar',
                    'position' => 'right',
                ],
            ],
            'categories' => [
                'necessary' => ['readOnly' => true],
                'analytics' => [
                    'enabled' => $optionalCategoriesEnabled,
                    'autoClear' => [
                        'cookies' => [
                            ['name' => '_ga'],
                            ['name' => '_gid'],
                            ['name' => '_gat'],
                        ],
                    ],
                ],
                'targeting' => [
                    'enabled' => $optionalCategoriesEnabled,
                    'autoClear' => [
                        'cookies' => [
                            ['name' => '_fbp'],
                            ['name' => 'CONSENT'],
                            ['name' => '__Secure.'],
                        ],
                    ],
                ],
            ],
            'language' => [
                'default' => array_key_first($languages),
                'autoDetect' => 'document',
                'translations' => $languages,
            ],
        ];

        $this->template->config = json_encode(
            $config,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    private function createCookieTable(Translator $translator, string $block, array $cookies): array
    {
        return [
            'headers' => [
                'name' => 'ID',
                'description' => $translator->translate('settings_modal.cookie_table_headers.col2'),
                'duration' => $translator->translate('settings_modal.cookie_table_headers.col3'),
            ],
            'body' => array_map(static function (array $cookie) use ($translator, $block): array {
                return [
                    'name' => $cookie[0],
                    'description' => $translator->translate(sprintf(
                        'settings_modal.blocks.%s.cookie_table.%s',
                        $block,
                        $cookie[1]
                    )),
                    'duration' => $cookie[2],
                ];
            }, $cookies),
        ];
    }

}
