<?php declare(strict_types = 1);

namespace Dravencms\CookieConsent;

use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
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

    /**
     * @return \WebLoader\Nette\CssLoader
     */
    public function createComponentCookieConsentCss(): CssLoader
    {
        return $this->webLoader->createCssLoader('cookieConsent');
    }

    /**
     * @return JavaScriptLoader
     */
    public function createComponentCookieConsentJs(): JavaScriptLoader
    {
        return $this->webLoader->createJavaScriptLoader('cookieConsent');
    }
}
