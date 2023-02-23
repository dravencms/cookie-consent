<?php declare(strict_types = 1);

namespace Dravencms\FrontModule\CookieConsentModule;

use Dravencms\Model\CookieConsent\Repository\SettingsRepository;
use Dravencms\BasePresenter;

/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class SettingsPresenter extends BasePresenter
{
    /** @var SettingsRepository @inject */
    public $settingsRepository;

    public function renderDefault(): void
    {
        //$this->template->settings = $this->settingsRepository->getActive();

        $languages = [
            'cs' => [
                'consent_modal' => [
                    'title' => 'Informace o cookies na této stránce.',
                    'description' => 'Soubory cookie používáme ke shromažďování a analýze informací o výkonu a používání webu, zajištění fungování funkcí ze sociálních médií a ke slepšení a přizpůsobení obsahu a reklam. Udělený souhlas lze kdykoliv odvolat kliknutím na odkaz “Nastavení cookies”. <a href="https://nemec.eu/cookies" class="cc-link">Informace o cookies</a> <a href="https://www.nemec.eu/ochrana-osobnich-udaju" class="cc-link">Ochrana osobních údajů</a>',
                    'primary_btn' => [
                        'text' => 'Souhlasím',
                        'role' => 'accept_all',
                    ],
                    'secondary_btn' => [
                        'text' => 'Nastavení cookies',
                        'role' => 'settings'
                    ],
                    'revision_message' => '<br><br> Milý návštěvníku, podmínky používání cookies se od vašeho minulého souhlasu změnily!'
                ],
                'settings_modal' => [
                    'title' => 'Nastavení cookies',
                    'save_settings_btn' => 'Uložit nastavení',
                    'accept_all_btn' => 'Přijmout vše',
                    'reject_all_btn' => 'Odmítnout vše',
                    'close_btn_label' => 'Zavřít',
                    'cookie_table_headers' => [
                        ['col1' => 'ID'],
                        ['col2' => 'Účel'],
                        ['col3' => 'Expirace']
                    ],
                    'blocks' => [
                        [
                            'title' => 'Předvolby ochrany osobních údajů',
                            'description' => 'Při návštěvě jakékoli webové stránky je pravděpodobné, že stránka získá nebo uloží informace ve vašem prohlížeči, a to většinou ve formě souborů cookie. ' .
                                'Můžou to být informace týkající se vás, vašich preferencí a zařízení, které používáte. Většinou to slouží k vylepšování stránky, aby fungovala podle vašich očekávání. ' .
                                'Informace vás zpravidla neidentifikují jako jednotlivce, ale celkově mohou pomoci přizpůsobovat prostředí vašim potřebám. ' .
                                'Respektujeme vaše právo na soukromí, a proto se můžete rozhodnout, že některé soubory cookie nebudete akceptovat. ' .
                                'Nezapomínejte ale na to, že zablokováním některých souborů cookie můžete ovlivnit, jak stránka funguje a jaké služby jsou vám nabízeny.'
                        ],
                        [
                            'title' => 'Naprosto nezbytné soubory cookie',
                            'description' => 'Jsou nezbytné k tomu, aby web fungoval, takže není možné je vypnout. ' .
                                'Většinou jsou nastavené jako odezva na akce, které jste provedli, jako je požadavek služeb týkajících se bezpečnostních nastavení, přihlašování, vyplňování formulářů atp. ' .
                                'Prohlížeč můžete nastavit tak, aby blokoval soubory cookie nebo o nich posílal upozornění. Mějte na paměti, že některé stránky bez těchto souborů nebudou fungovat. ' .
                                'Tyto soubory cookie neukládají žádně osobní identifikovatelné informace.',
                            'toggle' => [
                                'value' => 'necessary',
                                'enabled' => true,
                                'readonly' => true
                            ]
                        ],
                        [
                            'title' => 'Soubory cookie pro analýzu a zvýšení výkonu',
                            'description' => 'Pomáhají sledovat počet návštěvníků a také z jakého zdroje provoz pochází, což nám umožňuje zlepšovat výkon stránky. ' .
                                'Můžeme s nimi určovat, které stránky jsou nejoblíbenější a které nejsou oblíbené, a také sledovat, jakým způsobem se návštěvníci na webu pohybují. ' .
                                'Všechny informace, které soubory cookie shromažďují, jsou souhrnné a anonymní. Pokud soubory cookie nepovolíte, nebudeme vědět, kdy jste navštívili naši stránku.',
                            'toggle' => [
                                'value' => 'analytics',
                                'enabled' => false,
                                'readonly' => false
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '^_ga',
                                    'col2' => 'GoogleAnalytics: Používá se k rozeznávání uživatelů.',
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ],
                                [
                                    'col1' => '_gid',
                                    'col2' => 'GoogleAnalytics: Používá se k rozeznávání uživatelů.',
                                    'col3' => '1 year'
                                ],
                                [
                                    'col1' => '_gat',
                                    'col2' => 'GoogleAnalytics: Používá se ke kontrole počtu požadavků.',
                                    'col3' => '1 minute'
                                ]
                            ]
                        ], [
                            'title' => 'Soubory cookie sociálních sítí',
                            'description' => 'Soubory cookie jsou nastavovány službami sociálních médií, které jsme na stránku přidali, abyste náš obsah mohli sdílet s přáteli a dalšími sítěmi. ' .
                                'Mají schopnost sledovat prohlížeč i na jiných stránkách a budovat profil s přehledem vašich zájmů. Může to mít vliv na obsah a zprávy, které se zobrazí na dalších stránkách, jež navštívíte. ' .
                                'Pokud tyto soubory cookie nepovolíte, je možné, že se vám nezobrazí tyto nástroje na sdílení, anebo nebudou funkční.',
                            'toggle' => [
                                'value' => 'targeting',
                                'enabled' => false,
                                'readonly' => false,
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '_fbp',
                                    'col2' => 'Facebook: Používá se k rozeznávání uživatelů.',
                                    'col3' => '3 months',
                                ],
                                [
                                    'col1' => 'CONSENT',
                                    'col2' => 'Google: Ukládá informace o souhlasu s prohlášením o ochraně dat.',
                                    'col3' => '1 year',
                                ],
                                [
                                    'col1' => '__Secure.',
                                    'col2' => 'Builds a profile of website visitor interests to show relevant and personalized ads through retargeting.',
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ]
                            ]
                        ], [
                            'title' => 'Další informace',
                            'description' => '<a href="https:/nemec.eu/cookies" class="cc-link">Informace o souborech cookies</a> | <a href="https://www.nemec.eu/ochrana-osobnich-udaju" class="cc-link">Ochrana osobních údajů</a>.',
                        ]
                    ]
                ]
            ],
            'en' => [
                'consent_modal' => [
                    'title' => 'Information about cookies on this site.',
                    'description' => 'You can withdraw your consent at any time by clicking on the “Cookie settings” link. <a href="https://nemec.eu/en/cookies" class="cc-link">Information about cookies</a> <a href="https://nemec.eu/en/personal-data-protection" class="cc-link">Processing of personal data</a>',
                    'primary_btn' => [
                        'text' => 'I agree',
                        'role' => 'accept_all'
                    ],
                    'secondary_btn' => [
                        'text' => 'Cookies settings',
                        'role' => 'settings'
                    ],
                    'revision_message' => '<br><br> Dear user, terms and conditions have changed since the last time you visisted!'
                ],
                'settings_modal' => [
                    'title' => 'Cookie settings',
                    'save_settings_btn' => 'Save current selection',
                    'accept_all_btn' => 'Accept all',
                    'reject_all_btn' => 'Reject all',
                    'close_btn_label' => 'Close',
                    'cookie_table_headers' => [
                        ['col1' => 'ID'],
                        ['col2' => 'Purpose'],
                        ['col3' => 'Expiration']
                    ],
                    'blocks' => [
                        [
                            'title' => 'Personal data protection preferences',
                            'description' => 'When you visit any website, it is likely that the site will obtain or store information in your browser, usually in the form of cookies. ' .
                                'This may be information relating to you, your preferences and the device you are using. Most of the time this is used to improve the site so that it performs to your expectations. ' .
                                'The information does not usually identify you as an individual, but overall it can help to tailor the environment to your needs. We respect your right to privacy and you may choose not to accept some cookies. ' .
                                'However, please remember that by blocking some cookies you may be able to influence how the site works and what services are offered to you.'
                        ],
                        [
                            'title' => 'Strictly necessary cookies',
                            'description' => 'They are essential for the site to work, so it is not possible to turn them off. They are usually set in response to actions you take, such as requesting services related to security settings, ' .
                                'logging in, filling out forms, etc. You can set your browser to block cookies or send notifications about them. Keep in mind that some sites will not work without these files. ' .
                                'These cookies do not store any personally identifiable information.',
                            'toggle' => [
                                'value' => 'necessary',
                                'enabled' => true,
                                'readonly' => true
                            ]
                        ],
                        [
                            'title' => 'Analytics and performance cookies',
                            'description' => 'They help us track the number of visitors as well as the source of traffic, allowing us to improve site performance. ' .
                                'We can use them to determine which pages are most popular and which are unpopular, and to track how visitors move around the site. ' .
                                'All the information that cookies collect is aggregated and anonymous. If you do not allow cookies, we will not know when you visited our site.',
                            'toggle' => [
                                'value' => 'analytics',
                                'enabled' => false,
                                'readonly' => false
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '^_ga',
                                    'col2' => 'GoogleAnalytics: used for user recognition',
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ],
                                [
                                    'col1' => '_gid',
                                    'col2' => 'GoogleAnalytics: used for user recognition',
                                    'col3' => '1 year'
                                ],
                                [
                                    'col1' => '_gat',
                                    'col2' => 'GoogleAnalytics: used to control the number of requests',
                                    'col3' => '1 minute'
                                ]
                            ]
                        ], [
                            'title' => 'Targeting and advertising cookies',
                            'description' => 'These cookies are set by the social media services we have added to the site so that you can share our content with your friends and other networks. ' .
                                'They have the ability to track your browser on other sites and build a profile with an overview of your interests. ' .
                                'This may affect the content and messages that appear on other sites you visit. If you do not allow these cookies, you may not see these sharing tools or they may not work.',
                            'toggle' => [
                                'value' => 'targeting',
                                'enabled' => false,
                                'readonly' => false,
                            ],
                            'cookie_table' => [
                                [
                                    'col1' => '_fbp',
                                    'col2' => 'Facebook: It is used to indetify users.',
                                    'col3' => '3 months',
                                ],
                                [
                                    'col1' => 'CONSENT',
                                    'col2' => 'Google: indicates acceptance of Google\'s privacy review',
                                    'col3' => '1 year',
                                ],
                                [
                                    'col1' => '__Secure.',
                                    'col2' => 'Builds a profile of website visitor interests to show relevant and personalized ads through retargeting.',
                                    'col3' => '2 years',
                                    'is_regex' => true
                                ]
                            ]
                        ], [
                            'title' => 'More information',
                            'description' => '<a href="https://nemec.eu/en/cookies" class="cc-link">Information about cookies</a> | <a href="https://nemec.eu/en/personal-data-protection" class="cc-link">Processing of personal data</a>.',
                        ]
                    ]
                ]
            ]
        ];

        $this->template->languages = json_encode($languages);
    }

}