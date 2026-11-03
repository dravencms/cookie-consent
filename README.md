# DravenCMS Cookie Consent

Multilingual cookie-consent administration and frontend integration for DravenCMS. The package stores consent settings and translated modal content, publishes Vanilla CookieConsent assets, and generates the browser initialization script from the active configuration.

## Features

- Administration for multiple cookie-consent configurations.
- One active configuration at a time.
- Translated titles, descriptions, revision messages, and policy links.
- Opt-in and opt-out modes.
- Cookie lifetime, domain, automatic cleanup, page-script, and forced-consent settings.
- Reusable frontend settings button.
- Provider-defined admin navigation and ACL permissions.

## Installation

```bash
composer require dravencms/cookie-consent
```

The DravenCMS package loader loads `dravencms.config.neon` and publishes assets to `%wwwDir%/assets/cookieConsent`. The configuration registers presenters, Doctrine mappings, translations, routes, and frontend WebLoader files.

Apply the package's database schema through the application's migration workflow before enabling administration. Navigation and permission definitions come from providers.

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

Consent-managed scripts use Vanilla CookieConsent v3 attributes:

```html
<script type="text/plain" data-category="analytics" data-service="Google Analytics">
    // Loaded after analytics consent.
</script>
```

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

The ACL provider defines `cookieConsent.edit` and `cookieConsent.delete`; assign them explicitly through the group editor.

## License

This package is licensed under the LGPL-3.0-only license.

## Admin authorization and mutations

Administration uses User 3.1, Admin 3.2 and Application 0.3 guards. Screens and write callbacks enforce the existing provider-defined permissions server-side, including direct URLs and bulk callbacks. Mutating signals require POST and a session-bound action token; synchronize the matching admin assets when upgrading. See the User package's `user:security:migrate` instructions for the password-reset schema change.


## Public assets

Released packages include a generated `public/` directory. Packager 3 links it to
`www/assets/` on every Composer install/update; existing asset URLs stay the same.
Development sources, package metadata and tests are kept outside the public directory.

After editing assets, install Composer dependencies and run `composer build:assets`.
The explicit runtime file list is `assets/public-assets.json`; local CSS dependencies
are included automatically. Commit `public/` and `public-assets.manifest.json` with
the source changes. The builder rejects missing dependencies and locally modified
or unmanaged generated files. Admin's `npm run build:admin` also rebuilds public assets.
