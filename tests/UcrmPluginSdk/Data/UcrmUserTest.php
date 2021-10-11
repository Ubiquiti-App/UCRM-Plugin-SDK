<?php
/*
 * This file is part of UCRM Plugin SDK.
 *
 * Copyright (c) 2019 Ubiquiti Inc.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ubnt\UcrmPluginSdk\Data;

use Ubnt\UcrmPluginSdk\Security\Permission;
use Ubnt\UcrmPluginSdk\Security\PermissionNames;
use Ubnt\UcrmPluginSdk\Security\SpecialPermission;
use Ubnt\UcrmPluginSdk\Security\SpecialPermissionNames;

class UcrmUserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider hasViewPermissionProvider
     */
    public function testHasViewPermission(UcrmUser $ucrmUser, string $resource, bool $expected): void
    {
        self::assertSame($expected, $ucrmUser->hasViewPermission($resource));
    }

    /**
     * @return mixed[]
     */
    public function hasViewPermissionProvider(): array
    {
        return [
            'denied user' => [
                'ucrmUser' => $this->createDeniedUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => false,
            ],
            'view user' => [
                'ucrmUser' => $this->createViewUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => true,
            ],
            'edit user' => [
                'ucrmUser' => $this->createEditUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => true,
            ],
        ];
    }

    /**
     * @dataProvider hasEditPermissionProvider
     */
    public function testHasEditPermission(UcrmUser $ucrmUser, string $resource, bool $expected): void
    {
        self::assertSame($expected, $ucrmUser->hasEditPermission($resource));
    }

    /**
     * @return mixed[]
     */
    public function hasEditPermissionProvider(): array
    {
        return [
            'denied user' => [
                'ucrmUser' => $this->createDeniedUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => false,
            ],
            'view user' => [
                'ucrmUser' => $this->createViewUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => false,
            ],
            'edit user' => [
                'ucrmUser' => $this->createEditUcrmUser(),
                'resource' => PermissionNames::CLIENTS_CLIENTS,
                'expected' => true,
            ],
        ];
    }

    /**
     * @dataProvider hasSpecialPermissionProvider
     */
    public function testHasSpecialPermission(UcrmUser $ucrmUser, string $name, bool $expected): void
    {
        self::assertSame($expected, $ucrmUser->hasSpecialPermission($name));
    }

    /**
     * @return mixed[]
     */
    public function hasSpecialPermissionProvider(): array
    {
        return [
            'denied user' => [
                'ucrmUser' => $this->createDeniedUcrmUser(),
                'name' => SpecialPermissionNames::CLIENT_EXPORT,
                'expected' => false,
            ],
            'view user' => [
                'ucrmUser' => $this->createViewUcrmUser(),
                'name' => SpecialPermissionNames::CLIENT_EXPORT,
                'expected' => true,
            ],
            'edit user' => [
                'ucrmUser' => $this->createEditUcrmUser(),
                'name' => SpecialPermissionNames::CLIENT_EXPORT,
                'expected' => true,
            ],
        ];
    }

    private function createDeniedUcrmUser(): UcrmUser
    {
        return new UcrmUser(
            [
                'userId' => 1,
                'username' => 'admin',
                'isClient' => false,
                'clientId' => null,
                'userGroup' => 'Admin Group',
                'permissions' => [
                    PermissionNames::CLIENTS_CLIENTS => Permission::DENIED,
                ],
                'specialPermissions' => [
                    SpecialPermissionNames::CLIENT_EXPORT => SpecialPermission::DENY,
                ],
                'locale' => 'en_US',
            ]
        );
    }

    private function createViewUcrmUser(): UcrmUser
    {
        return new UcrmUser(
            [
                'userId' => 1,
                'username' => 'admin',
                'isClient' => false,
                'clientId' => null,
                'userGroup' => 'Admin Group',
                'permissions' => [
                    PermissionNames::CLIENTS_CLIENTS => Permission::VIEW,
                ],
                'specialPermissions' => [
                    SpecialPermissionNames::CLIENT_EXPORT => SpecialPermission::ALLOW,
                ],
                'locale' => 'en_US',
            ]
        );
    }

    private function createEditUcrmUser(): UcrmUser
    {
        return new UcrmUser(
            [
                'userId' => 1,
                'username' => 'admin',
                'isClient' => false,
                'clientId' => null,
                'userGroup' => 'Admin Group',
                'permissions' => [
                    PermissionNames::CLIENTS_CLIENTS => Permission::EDIT,
                ],
                'specialPermissions' => [
                    SpecialPermissionNames::CLIENT_EXPORT => SpecialPermission::ALLOW,
                ],
                'locale' => 'en_US',
            ]
        );
    }
}
