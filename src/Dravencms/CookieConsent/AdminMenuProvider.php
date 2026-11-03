<?php declare(strict_types=1);

namespace Dravencms\CookieConsent;

use Dravencms\Admin\Menu\{AdminMenuItem, AdminMenuProviderInterface};

final class AdminMenuProvider implements AdminMenuProviderInterface
{
    public function getItems(): iterable
    {
        yield new AdminMenuItem('cookieConsent.overview', 'cookieConsent.menu.overview', destination: ':Admin:CookieConsent:Settings:default', icon: 'fa-cookie-bite', order: 700, resource: 'cookieConsent', privilege: 'edit');
    }
}
