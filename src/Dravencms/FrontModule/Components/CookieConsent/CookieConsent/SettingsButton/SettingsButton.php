<?php declare(strict_types = 1);
namespace Dravencms\FrontModule\Components\CookieConsent\CookieConsent\SettingsButton;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Dravencms\Components\BaseControl\BaseControl;
use Dravencms\Model\CookieConsent\Entities\TrackingService;
use Dravencms\Model\CookieConsent\Repository\TrackingRepository;

/**
 * Class Tracking
 * @package FrontModule\Components\CookieConsent
 */
class SettingsButton extends BaseControl
{
    /** @var TrackingRepository */
    private $trackingRepository;

    public function __construct(TrackingRepository $trackingRepository)
    {
        $this->trackingRepository = $trackingRepository;
    }

    public function renderHeader(): void
    {
        $template = $this->template;
        $template->trackings = $this->trackingRepository->getByPosition(TrackingService::POSITION_HEADER);
        $template->setFile(__DIR__.'/tracking.latte');
        $template->render();
    }

    public function renderFooter(): void
    {
        $template = $this->template;
        $template->trackings = $this->trackingRepository->getByPosition(TrackingService::POSITION_BODY_BOTTOM);
        $template->setFile(__DIR__.'/tracking.latte');
        $template->render();
    }
}
