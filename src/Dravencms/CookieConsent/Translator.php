<?php declare(strict_types = 1);

namespace Dravencms\CookieConsent;

use Nette\SmartObject;
use Nette\Localization\ITranslator;
use Dravencms\Model\Locale\Entities\Locale;

/**
 * Class Translator
 * @package Dravencms\Translator
 */
class Translator
{
    use SmartObject;

    /**
     * @var Locale
     */
    private $locale;

    /**
     * @var ITranslator
     */
    private $translator;

    /**
     * @var string
     */
    private $domain;

    public function __construct(Locale $locale, ITranslator $translator, string $domain)
    {
        $this->locale = $locale;
        $this->translator = $translator;
        $this->domain = $domain;
    }

    public function translate(string $key, array $parameters = []): string {
        return $this->translator->translate($key, $parameters, $this->domain, $this->locale->getLanguageCode());
    }
}
