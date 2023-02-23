<?php declare(strict_types = 1);
namespace Dravencms\FrontModule\Components\CookieConsent\CookieConsent\SettingsButton;

use Dravencms\Components\BaseControl\BaseControl;
use Dravencms\Model\CookieConsent\Repository\SettingsRepository;

/**
 * Class Tracking
 * @package FrontModule\Components\CookieConsent
 */
class SettingsButton extends BaseControl
{
    /** @var SettingsRepository */
    public $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository) 
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function render(): void
    {
        $settings = $this->settingsRepository->getOneByActive();
        $template = $this->template;
        $template->settings = $settings;
        $template->setFile(__DIR__.'/button.latte');
        $template->render();
    }

}
