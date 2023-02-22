# Dravencms Cookie Consent module

This is a SEO module for dravencms

## Instalation

The best way to install dravencms/cookie-consent is using  [Composer](http://getcomposer.org/):


```sh
$ composer require dravencms/cookie-consent
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	dravencms.cookieConsent: Dravencms\CookieConsent\DI\CookieConsentExtension
```
