<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

 namespace Dravencms\Model\CookieConsent\Entities;

use Doctrine\ORM\Mapping as ORM;
use Dravencms\Model\Locale\Entities\Locale;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Dravencms\Database\Attributes\Identifier;
use Nette;

/**
 * Class SettingsTranslation
 * @ORM\Entity
 * @ORM\Table(name="cookieConsentSettingsTranslation")
 */
class SettingsTranslation
{
    use Nette\SmartObject;
    use Identifier;
    use TimestampableEntity;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text",nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $revisionMessage;

    /**
     * @var string
     * @ORM\Column(type="text",nullable=false)
     */
    private $personalDataProtectionUrl;

    /**
     * @var string
     * @ORM\Column(type="text",nullable=false)
     */
    private $cookiesInformationUrl;


    /**
     * @var Settings
     * @ORM\ManyToOne(targetEntity="Settings", inversedBy="translations")
     * @ORM\JoinColumn(name="settings_id", referencedColumnName="id")
     */
    private $settings;

    /**
     * @var Locale
     * @ORM\ManyToOne(targetEntity="Dravencms\Model\Locale\Entities\Locale")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;

    /**
     * SettingsTranslation constructor.
     * @param Settings $settings
     * @param Locale $locale
     * @param $title
     * @param $description
     * @param $detailButtonText
     */
    public function __construct(
        Settings $settings, 
        Locale $locale, 
        string $title, 
        string $description, 
        string $revisionMessage,
        string $personalDataProtectionUrl,
        string $cookiesInformationUrl,
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->revisionMessage = $revisionMessage;
        $this->personalDataProtectionUrl = $personalDataProtectionUrl;
        $this->cookiesInformationUrl = $cookiesInformationUrl;
        $this->settings = $settings;
        $this->locale = $locale;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $revisionMessage
     */
    public function setRevisionMessage(string $revisionMessage): void
    {
        $this->revisionMessage = $revisionMessage;
    }

    /**
     * @param string $personalDataProtectionUrl
     */
    public function setPersonalDataProtectionUrl(string $personalDataProtectionUrl): void
    {
        $this->personalDataProtectionUrl = $personalDataProtectionUrl;
    }

    /**
     * @param string $cookiesInformationUrl
     */
    public function setCookiesInformationUrl(string $cookiesInformationUrl): void
    {
        $this->cookiesInformationUrl = $cookiesInformationUrl;
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Settings $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getRevisionMessage(): string
    {
        return $this->revisionMessage;
    }

    /**
     * @return string
     */
    public function getPersonalDataProtectionUrl(): string
    {
        return $this->personalDataProtectionUrl;
    }

    /**
     * @return string
     */
    public function getCookiesInformationUrl(): string
    {
        return $this->cookiesInformationUrl;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }
}