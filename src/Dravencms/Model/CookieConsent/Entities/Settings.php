<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Model\CookieConsent\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Dravencms\Database\Attributes\Identifier;
use Nette;

/**
 * Class Settings
 * @package Dravencms\Model\CookieConsent\Entities
 * @ORM\Entity
 * @ORM\Table(name="cookieConsentSettings")
 */
class Settings
{
    use Nette\SmartObject;
    use Identifier;
    use TimestampableEntity;

    const MODE_OPT_IN = 'opt-in';
    const MODE_OPT_OUT = 'opt-out';

    /**
     * @var string
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    private $identifier;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isAutoclearCookies;

    /**
     * @var integer
     * @ORM\Column(type="integer",nullable=false)
     */
    private $cookieExpiration;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPageScripts;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isForceConsent;

    /**
     * @var string
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    private $mode;

    /**
     * @var string
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $cookieDomain;

    /**
     * @var ArrayCollection|SettingsTranslation[]
     * @ORM\OneToMany(targetEntity="SettingsTranslation", mappedBy="settings",cascade={"persist", "remove"})
     */
    private $translations;


    /**
     * Robots constructor.
     * @param string $identifier
     * @param string $path
     * @param bool $isActive
     * @param string $action
     */
    public function __construct(
        string $identifier, 
        bool $isActive = true,
        bool $isAutoclearCookies = true,
        int $cookieExpiration = 365,
        bool $isPageScripts = true,
        bool $isForceConsent = false,
        string $mode = self::MODE_OPT_OUT,
        string $cookieDomain = null
    )
    {
        $this->identifier = $identifier;
        $this->isActive = $isActive;
        $this->isAutoclearCookies = $isAutoclearCookies;
        $this->cookieExpiration = $cookieExpiration;
        $this->isPageScripts = $isPageScripts;
        $this->isForceConsent = $isForceConsent;
        $this->cookieDomain = $cookieDomain;
        $this->setMode($mode);

        $this->translations = new ArrayCollection();
    }


    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @param boolean $isAutoclearCookies
     */
    public function setIsAutoclearCookies(bool $isAutoclearCookies): void
    {
        $this->isAutoclearCookies = $isAutoclearCookies;
    }

    /**
     * @param int $cookieExpiration
     */
    public function setCookieExpiration(int $cookieExpiration): void
    {
        $this->cookieExpiration = $cookieExpiration;
    }

    /**
     * @param boolean $isPageScripts
     */
    public function setIsPageScripts(bool $isPageScripts): void
    {
        $this->isPageScripts = $isPageScripts;
    }

    /**
     * @param boolean $isForceConsent
     */
    public function setIsForceConsent(bool $isForceConsent): void
    {
        $this->isForceConsent = $isForceConsent;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        if (!in_array($mode, [self::MODE_OPT_OUT, self::MODE_OPT_IN])) {
            throw new \InvalidArgumentException("Invalid $mode");
        }
        $this->mode = $mode;
    }

    /**
     * @param string|null $cookieDomain
     */
    public function setCookieDomain(?string $cookieDomain): void
    {
        $this->cookieDomain = $cookieDomain;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return boolean
     */
    public function isAutoclearCookies(): bool
    {
        return $this->isAutoclearCookies;
    }

    /**
     * @return integer
     */
    public function getCookieExpiration(): int
    {
        return $this->cookieExpiration;
    }

    /**
     * @return boolean
     */
    public function isPageScripts(): bool
    {
        return $this->isPageScripts;
    }

    /**
     * @return boolean
     */
    public function isForceConsent(): bool
    {
        return $this->isForceConsent;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return string|null
     */
    public function getCookieDomain(): ?string
    {
        return $this->cookieDomain;
    }

    /**
     * @return ArrayCollection|SettingsTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}