<?php declare(strict_types = 1);

namespace Dravencms\CookieConsent;

use Dravencms\FrontModule\Components\CookieConsent\CookieConsent\SettingsButton\SettingsButtonFactory;
use Dravencms\FrontModule\Components\CookieConsent\CookieConsent\SettingsButton\SettingsButton;

trait TCookieConsentPresenter
{
    /** @var SettingsButtonFactory */
    public $settingsButtonFactory;

    /**
     * @param SettingsButtonFactory $settingsButtonFactory
     */
    public function injectCookieConsentSettingsButtonFactory(SettingsButtonFactory $settingsButtonFactory): void
    {
        $this->settingsButtonFactory = $settingsButtonFactory;
    }

    /**
     * @return SettingsButton
     */
    public function createComponentCookieConsentSettingsButton(): SettingsButton
    {
        return $this->settingsButtonFactory->create();
    }
}
