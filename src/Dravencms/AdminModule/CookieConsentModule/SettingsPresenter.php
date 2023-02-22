<?php declare(strict_types = 1);

namespace Dravencms\AdminModule\CookieConsentModule;


use Dravencms\AdminModule\Components\CookieConsent\SettingsForm\SettingsFormFactory;
use Dravencms\AdminModule\Components\CookieConsent\SettingsForm\SettingsForm;
use Dravencms\AdminModule\Components\CookieConsent\SettingsGrid\SettingsGridFactory;
use Dravencms\AdminModule\Components\CookieConsent\SettingsGrid\SettingsGrid;
use Dravencms\AdminModule\SecuredPresenter;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Repository\ettingsRepository;

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
        $this->template->h1 = 'Settings';
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
            $this->template->h1 = sprintf('Edit settings „%s“', $settings->getIdentifier());
        } else {
            $this->template->h1 = 'New settings';
        }
    }

    /**
     * @return SettingsForm
     */
    protected function createComponentFormSettings(): SettingsForm
    {
        $control = $this->settingsFormFactory->create($this->robots);
        $control->onSuccess[] = function()
        {
            $this->flashMessage('Settings has been successfully saved', 'alert-success');
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
            $this->flashMessage('Settings has been successfully deleted', 'alert-success');
            $this->redirect('Settings:');
        };
        return $control;
    }
}
