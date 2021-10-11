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

/**
 * This data class holds information of user currently logged into UCRM.
 * You can use UcrmSecurity class to get it.
 *
 * @see \Ubnt\UcrmPluginSdk\Service\UcrmSecurity
 * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/security.md
 */
class UcrmUser
{
    /**
     * ID of the logged in user.
     *
     * @var int
     */
    public $userId;

    /**
     * Username used to log in to UCRM.
     *
     * @var string
     */
    public $username;

    /**
     * Will be true if the logged in user is client, false in case of admin.
     *
     * @var bool
     */
    public $isClient;

    /**
     * ID of the logged in client.
     *
     * NULL if the user is admin.
     *
     * @var int|null
     */
    public $clientId;

    /**
     * Name of the user group where the user belongs.
     * NULL if the user is client.
     *
     * @var string|null
     */
    public $userGroup;

    /**
     * List of user's permissions. Can have the following values:
     *   - view
     *   - edit
     *   - denied
     *
     * Empty if the user is client.
     *
     * @see PermissionNames class for possible keys (permission names).
     * @see Permission class for possible values.
     *
     * @var string[]
     */
    public $permissions = [];

    /**
     * List of user's special permissions. Can have the following values:
     *   - allow
     *   - deny
     *
     * Empty if the user is client.
     *
     * @see SpecialPermissionNames class for possible keys (special permission names).
     * @see SpecialPermission class for possible values.
     *
     * @var string[]
     */
    public $specialPermissions = [];

    /**
     * Locale code.
     *
     * @var string
     */
    public $locale;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->userId = $data['userId'];
        $this->username = $data['username'];
        $this->isClient = $data['isClient'];
        $this->clientId = $data['clientId'];
        $this->userGroup = $data['userGroup'];
        $this->permissions = $data['permissions'] ?? [];
        $this->specialPermissions = $data['specialPermissions'] ?? [];
        $this->locale = $data['locale'];
    }

    /**
     * Returns true if the user has view permission for given resource.
     * Will always return false if the user is client.
     */
    public function hasViewPermission(string $resource): bool
    {
        return array_key_exists($resource, $this->permissions)
            && in_array($this->permissions[$resource], [Permission::VIEW, Permission::EDIT], true);
    }

    /**
     * Returns true if the user has edit permission for given resource.
     * Will always return false if the user is client.
     */
    public function hasEditPermission(string $resource): bool
    {
        return array_key_exists($resource, $this->permissions)
            && $this->permissions[$resource] === Permission::EDIT;
    }

    /**
     * Returns true if the user has special permission allowed.
     * Will always return false if the user is client.
     */
    public function hasSpecialPermission(string $name): bool
    {
        return array_key_exists($name, $this->specialPermissions)
            && $this->specialPermissions[$name] === SpecialPermission::ALLOW;
    }
}
