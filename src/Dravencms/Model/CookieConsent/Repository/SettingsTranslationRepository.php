<?php declare(strict_types = 1);

namespace Dravencms\Model\CookieConsent\Repository;

use Dravencms\Model\Locale\Entities\ILocale;
use Dravencms\Model\Locale\Entities\Locale;
use Dravencms\Database\EntityManager;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Entities\SettingsTranslation;

class SettingsTranslationRepository
{
    /** @var \Doctrine\Persistence\ObjectRepository|SettingsTranslation */
    private $settingsTranslationRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * SettingsTranslationRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->settingsTranslationRepository = $entityManager->getRepository(SettingsTranslation::class);
    }

    /**
     * @param Settings $settings
     * @param ILocale $locale
     * @return null|SettingsTranslation
     */
    public function getTranslation(Settings $settings, ILocale $locale): ?SettingsTranslation
    {
        return $this->settingsTranslationRepository->findOneBy(['settings' => $settings, 'locale' => $locale]);
    }

    /**
     * @param $parameters
     * @return SettingsTranslation|null
     */
    public function getOneByParameters(array $parameters): ?SettingsTranslation
    {
        return $this->settingsTranslationRepository->findOneBy($parameters);
    }

    /**
     * @param ILocale $locale
     * @return SettingsTranslation[]
     */
    public function getAll(ILocale $locale)
    {
        return $this->settingsTranslationRepository->findBy(['locale' => $locale]);
    }
}