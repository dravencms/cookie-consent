<?php declare(strict_types=1);

namespace Dravencms\CookieConsent;

use Dravencms\User\Acl\{AclPermission, AclProviderInterface};

final class AclProvider implements AclProviderInterface
{
    public function getPermissions(): iterable
    {
        yield new AclPermission('cookieConsent', 'edit', 'cookieConsent.acl.permissions.edit.label', resourceLabel: 'cookieConsent.acl.resource');
        yield new AclPermission('cookieConsent', 'delete', 'cookieConsent.acl.permissions.delete.label', resourceLabel: 'cookieConsent.acl.resource');
    }
}
