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
 * This class has constants for all special permission values, that can appear in $specialPermissions array of UcrmUser.
 *
 * @see UcrmUser::$specialPermissions
 */
class SpecialPermission
{
    public const ALLOW = 'allow';
    public const DENY = 'deny';
}
