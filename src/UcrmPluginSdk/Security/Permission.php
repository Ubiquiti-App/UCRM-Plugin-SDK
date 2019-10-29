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

namespace Ubnt\UcrmPluginSdk\Security;

use Ubnt\UcrmPluginSdk\Data\UcrmUser;

/**
 * This class has constants for all permission values, that can appear in $permissions array of UcrmUser.
 *
 * @see UcrmUser::$permissions
 */
class Permission
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DENIED = 'denied';
}
