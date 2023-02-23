<?php declare(strict_types = 1);

namespace Dravencms\FrontModule\CookieConsentModule;

use Dravencms\Model\Locale\Entities\Locale;
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

        $languages = [];
        foreach($settings->getTranslations() AS $settingsTranslation) {
            $translator = new Translator($settingsTranslation->getLocale(), $this->translator, 'cookieConsent');
            $languageCode = $settingsTranslation->getLocale()->getLanguageCode();
            $languages[$languageCode] = [
                'consent_modal' => [
                    'title' => $settingsTranslation->getTitle(),
                    'description' => $settingsTranslation->getDescription(),
                    'primary_btn' => [
                        'text' => $translator->translate('consent_modal.primary_btn.text'),
                        'role' => 'accept_all',
                    ],
                    'secondary_btn' => [
                        'text' => $translator->translate('consent_modal.secondary_btn.text'),
                        'role' => 'settings'
                    ],
                    'revision_message' => $settingsTranslation->getRevisionMessage()
                ],
                'settings_modal' => [
                    'title' => $translator->translate('settings_modal.title'),
                    'save_settings_btn' => $translator->translate('settings_modal.save_settings_btn'),
                    'accept_all_btn' => $translator->translate('settings_modal.accept_all_btn'),
                    'reject_all_btn' => $translator->translate('settings_modal.reject_all_btn'),
                    'close_btn_label' => $translator->translate('settings_modal.close_btn_label'),
                    'cookie_table_headers' => [
                        ['col1' => 'ID'],
                        ['col2' => $translator->translate('settings_modal.cookie_table_headers.col2')],
                        ['col3' => $translator->translate('settings_modal.cookie_table_headers.col3')]
                    ],
                    'blocks' => [
                        [
                            'title' => $translator->translate('settings_modal.blocks.b0.title'),
                            'description' => $translator->translate('settings_modal.blocks.b0.description')
                        ],
                        [
                            'title' => $translator->translate('settings_modal.blocks.b1.title'),
                            'description' => $translator->translate('settings_modal.blocks.b1.description'),
                            'toggle' => [
                                'value' => 'necessary',
                                'enabled' => true,
                                'readonly' => true
                            ]
                        ],
                        [
                            'title' => $translator->translate('settings_modal.blocks.b2.title'),
                            'description' => $translator->translate('settings_modal.blocks.b2.description'),
                            'toggle' => [
                                'value' => 'analytics',
                                'enabled' => false,
                                'readonly' => false
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '^_ga',
                                    'col2' => $translator->translate('settings_modal.blocks.b2.cookie_table._ga'),
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ],
                                [
                                    'col1' => '_gid',
                                    'col2' => $translator->translate('settings_modal.blocks.b2.cookie_table._gid'),
                                    'col3' => '1 year'
                                ],
                                [
                                    'col1' => '_gat',
                                    'col2' => $translator->translate('settings_modal.blocks.b2.cookie_table._gat'),
                                    'col3' => '1 minute'
                                ]
                            ]
                        ], [
                            'title' => $translator->translate('settings_modal.blocks.b3.title'),
                            'description' => $translator->translate('settings_modal.blocks.b3.description'),
                            'toggle' => [
                                'value' => 'targeting',
                                'enabled' => false,
                                'readonly' => false,
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '_fbp',
                                    'col2' => $translator->translate('settings_modal.blocks.b3.cookie_table._fbp'),
                                    'col3' => '3 months',
                                ],
                                [
                                    'col1' => 'CONSENT',
                                    'col2' => $translator->translate('settings_modal.blocks.b3.cookie_table.CONSENT'),
                                    'col3' => '1 year',
                                ],
                                [
                                    'col1' => '__Secure.',
                                    'col2' => $translator->translate('settings_modal.blocks.b3.cookie_table.__Secure'),
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ]
                            ]
                        ], [
                            'title' => $translator->translate('settings_modal.blocks.b4.title'),
                            'description' => $translator->translate('settings_modal.blocks.b4.description', [
                                'cookiesInformationUrl' => $settingsTranslation->getCookiesInformationUrl(),
                                'personalDataProtectionUrl' => $settingsTranslation->getPersonalDataProtectionUrl(),
                            ])
                        ]
                    ]
                ]
            ];
        }

        $this->template->languages = json_encode($languages);
    }

}