# DravenCMS Cookie Consent

Multilingual cookie-consent administration and frontend integration for DravenCMS. The package stores consent settings and translated modal content, publishes Vanilla CookieConsent assets, and generates the browser initialization script from the active configuration.

## Features

- Administration for multiple cookie-consent configurations.
- One active configuration at a time.
- Translated titles, descriptions, revision messages, and policy links.
- Opt-in and opt-out modes.
- Cookie lifetime, domain, automatic cleanup, page-script, and forced-consent settings.
- Reusable frontend settings button.
- Admin menu and ACL fixtures.

## Installation

```bash
composer require dravencms/cookie-consent
```

The DravenCMS package loader loads `dravencms.config.neon` and publishes assets to `%wwwDir%/assets/cookieConsent`. The configuration registers presenters, Doctrine mappings, translations, routes, and frontend WebLoader files.

Apply the package's database schema through the application's migration workflow and load its fixtures when the default admin menu and ACL operations are required.

## Frontend Integration

Add `TCookieConsentPresenter` to a shared frontend presenter:

```php
use Dravencms\CookieConsent\TCookieConsentPresenter;
use Dravencms\FrontModule\BasePresenter;

abstract class FrontPresenter extends BasePresenter
{
    use TCookieConsentPresenter;
}
```

Render the settings button wherever visitors should be able to reopen their consent preferences:

```latte
{control cookieConsentSettingsButton}
```

The button is rendered only when an active configuration exists. The package's frontend bundle loads Vanilla CookieConsent, package styling, and the generated `/cookieconsent-init` script automatically.

## Administration

Create a configuration for every required locale, then set one configuration active. Available settings include:

- Consent mode (`opt-in` or `opt-out`).
- Cookie expiration in days and optional cookie domain.
- Automatic cookie cleanup.
- Page-script handling.
- Forced consent.
- Translated consent text and privacy/cookie-information URLs.

Activating one configuration automatically deactivates the others.

## Public Route

`/cookieconsent-init` returns the JavaScript configuration generated from the active database record. Do not cache this route longer than the application can tolerate stale consent settings.

## Permissions

Fixtures define the `cookieConsent` ACL resource with `edit` and `delete` operations and grant them to the administrator group.

## License

This package is licensed under the LGPL-3.0-only license.
