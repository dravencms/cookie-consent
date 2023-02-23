<?php declare(strict_types = 1);
namespace Dravencms\FrontModule\Components\CookieConsent\CookieConsent\SettingsButton;

use Dravencms\Components\BaseControl\BaseControl;

/**
 * Class Tracking
 * @package FrontModule\Components\CookieConsent
 */
class SettingsButton extends BaseControl
{
    public function render(): void
    {
        $template = $this->template;
        $template->setFile(__DIR__.'/button.latte');
        $template->render();
    }

}
