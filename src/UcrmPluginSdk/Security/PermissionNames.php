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
 * This class has constants for all permission names, that can appear in $permissions array of UcrmUser.
 *
 * @see UcrmUser::$permissions
 */
class PermissionNames
{
    // Clients
    public const CLIENTS_CLIENTS = 'clients/clients';
    public const CLIENTS_DOCUMENTS = 'clients/documents';
    public const CLIENTS_SERVICES = 'clients/services';

    // Billing
    public const BILLING_INVOICES = 'billing/invoices';
    public const BILLING_QUOTES = 'billing/quotes';
    public const BILLING_PAYMENTS = 'billing/payments';
    public const BILLING_REFUNDS = 'billing/refunds';
    public const BILLING_CREDIT_NOTES = 'billing/credit_notes';

    // Scheduling
    public const SCHEDULING_MY_JOBS = 'scheduling/my_jobs';
    public const SCHEDULING_ALL_JOBS = 'scheduling/all_jobs';

    // Ticketing
    public const TICKETING_TICKETING = 'ticketing/ticketing';

    // Network
    public const NETWORK_SITES = 'network/sites';
    public const NETWORK_DEVICES = 'network/devices';
    public const NETWORK_DEVICE_INTERFACES = 'network/device_interfaces';
    public const NETWORK_OUTAGES = 'network/outages';
    public const NETWORK_UNKNOWN_DEVICES = 'network/unknown_devices';
    public const NETWORK_NETWORK_MAP = 'network/network_map';

    // Reports
    public const REPORTS_TAXES = 'reports/taxes';
    public const REPORTS_INVOICED_REVENUE = 'reports/invoiced_revenue';
    public const REPORTS_DATA_USAGE = 'reports/data_usage';

    // System
    public const SYSTEM_ORGANIZATIONS = 'system/organizations';
    public const SYSTEM_SETTINGS = 'system/settings';
    public const SYSTEM_WEBHOOKS = 'system/webhooks';
    public const SYSTEM_PLUGINS = 'system/plugins';

    // System - Items
    public const SYSTEM_ITEMS_SERVICE_PLANS = 'system/items/service_plans';
    public const SYSTEM_ITEMS_PRODUCTS = 'system/items/products';
    public const SYSTEM_ITEMS_SURCHARGES = 'system/items/surcharges';

    // System - Billing
    public const SYSTEM_BILLING_INVOICING = 'system/billing/invoicing';
    public const SYSTEM_BILLING_SUSPENSION = 'system/billing/suspension';
    public const SYSTEM_BILLING_FEES = 'system/billing/fees';
    public const SYSTEM_BILLING_ORGANIZATION_BANK_ACCOUNTS = 'system/billing/organization_bank_accounts';
    public const SYSTEM_BILLING_TAXES = 'system/billing/taxes';

    // System - Customization
    public const SYSTEM_CUSTOMIZATION_EMAIL_TEMPLATES = 'system/customization/email_templates';
    public const SYSTEM_CUSTOMIZATION_SUSPENSION_TEMPLATES = 'system/customization/suspension_templates';
    public const SYSTEM_CUSTOMIZATION_NOTIFICATION_SETTINGS = 'system/customization/notification_settings';
    public const SYSTEM_CUSTOMIZATION_INVOICE_TEMPLATES = 'system/customization/invoice_templates';
    public const SYSTEM_CUSTOMIZATION_ACCOUNT_STATEMENT_TEMPLATES = 'system/customization/account_statement_templates';
    public const SYSTEM_CUSTOMIZATION_QUOTE_TEMPLATES = 'system/customization/quote_templates';
    public const SYSTEM_CUSTOMIZATION_PAYMENT_RECEIPT_TEMPLATES = 'system/customization/payment_receipt_templates';
    public const SYSTEM_CUSTOMIZATION_APPEARANCE = 'system/customization/appearance';
    public const SYSTEM_CUSTOMIZATION_CLIENT_ZONE_PAGES = 'system/customization/client_zone_pages';

    // System - Tools
    public const SYSTEM_TOOLS_BACKUP = 'system/tools/backup';
    public const SYSTEM_TOOLS_CLIENT_IMPORT = 'system/tools/client_import';
    public const SYSTEM_TOOLS_PAYMENT_IMPORT = 'system/tools/payment_import';
    public const SYSTEM_TOOLS_SSL_CERTIFICATE = 'system/tools/ssl_certificate';
    public const SYSTEM_TOOLS_WEBROOT = 'system/tools/webroot';
    public const SYSTEM_TOOLS_DOWNLOADS = 'system/tools/downloads';
    public const SYSTEM_TOOLS_FCC_REPORTS = 'system/tools/fcc_reports';
    public const SYSTEM_TOOLS_UPDATES = 'system/tools/updates';
    public const SYSTEM_TOOLS_MAILING = 'system/tools/mailing';

    // System - Security
    public const SYSTEM_SECURITY_USERS = 'system/security/users';
    public const SYSTEM_SECURITY_GROUPS_AND_PERMISSIONS = 'system/security/groups_and_permissions';
    public const SYSTEM_SECURITY_APP_KEYS = 'system/security/app_keys';

    // System - Logs
    public const SYSTEM_LOGS_DEVICE_LOG = 'system/logs/device_log';
    public const SYSTEM_LOGS_EMAIL_LOG = 'system/logs/email_log';
    public const SYSTEM_LOGS_SYSTEM_LOG = 'system/logs/system_log';

    // System - Other
    public const SYSTEM_OTHER_VENDORS = 'system/other/vendors';
    public const SYSTEM_OTHER_REASONS_FOR_SUSPENDING_SERVICE = 'system/other/reasons_for_suspending_service';
    public const SYSTEM_OTHER_CUSTOM_ATTRIBUTES = 'system/other/custom_attributes';
    public const SYSTEM_OTHER_CLIENT_TAGS = 'system/other/client_tags';
    public const SYSTEM_OTHER_CONTACT_TYPES = 'system/other/contact_types';
    public const SYSTEM_OTHER_SANDBOX_TERMINATION = 'system/other/sandbox_termination';
}
