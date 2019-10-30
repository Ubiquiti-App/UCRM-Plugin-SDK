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

/**
 * This class has constants for all special permission names, that can appear in $specialPermissions array of UcrmUser.
 *
 * @see UcrmUser::$specialPermissions
 */
class SpecialPermissionNames
{
    public const CLIENTS_FINANCIAL_INFORMATION = 'special/clients_financial_information';
    public const CLIENT_EXPORT = 'special/client_export';
    public const CLIENT_IMPERSONATION = 'special/client_impersonation';
    public const CLIENT_LOG_EDIT_DELETE = 'special/client_log_edit_delete';
    public const JOB_COMMENT_EDIT_DELETE = 'special/job_comment_edit_delete';
    public const SHOW_DEVICE_PASSWORDS = 'special/show_device_passwords';
    public const VIEW_FINANCIAL_INFORMATION = 'special/view_financial_information';
}
