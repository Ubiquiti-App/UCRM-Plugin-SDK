# UCRM Plugin SDK
[![Coverage](https://img.shields.io/coveralls/github/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://coveralls.io/github/Ubiquiti-App/UCRM-Plugin-SDK)
[![Latest Release](https://img.shields.io/github/release/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://packagist.org/packages/ubnt/ucrm-plugin-sdk)
[![License](https://img.shields.io/github/license/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://packagist.org/packages/ubnt/ucrm-plugin-sdk)

This repository contains the open source PHP SDK for [UCRM plugins](https://github.com/Ubiquiti-App/UCRM-plugins).

## Installation
The UCRM Plugin SDK can be installed with [Composer](https://getcomposer.org/). Run this command:
```
composer require ubnt/ucrm-plugin-sdk
```

## Available classes

Class name | Description
---------- | -----------
[Ubnt\UcrmPluginSdk\Service\UcrmApi](src/UcrmPluginSdk/Service/UcrmApi.php) | A service that handles calling UCRM API. 
[Ubnt\UcrmPluginSdk\Service\UnmsApi](src/UcrmPluginSdk/Service/UnmsApi.php) | A service that handles calling UNMS API. 
[Ubnt\UcrmPluginSdk\Service\UcrmSecurity](src/UcrmPluginSdk/Service/UcrmSecurity.php) | A service that handles getting data of user currently logged into UCRM.
[Ubnt\UcrmPluginSdk\Service\PluginLogManager](src/UcrmPluginSdk/Service/PluginLogManager.php) | A service that handles managing the plugin's log file.
[Ubnt\UcrmPluginSdk\Service\UcrmOptionsManager](src/UcrmPluginSdk/Service/UcrmOptionsManager.php) | A service that handles loading automatically generated options available to the plugin.
[Ubnt\UcrmPluginSdk\Service\PluginConfigManager](src/UcrmPluginSdk/Service/PluginConfigManager.php) | A service that handles loading configuration of the plugin as defined in the plugin's manifest file.

## Usage
Simple example using available SDK classes:
```php
require_once __DIR__ . '/vendor/autoload.php';

// Get UCRM log manager.
$log = \Ubnt\UcrmPluginSdk\Service\PluginLogManager::create();

// Check if there is a user logged into UCRM and has permission to view invoices.
// https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/security.md
$security = \Ubnt\UcrmPluginSdk\Service\UcrmSecurity::create();
$user = $security->getUser();
if (
    ! $user
    || ! $user->hasViewPermission(\Ubnt\UcrmPluginSdk\Security\PermissionNames::BILLING_INVOICES)
) {
    if (! headers_sent()) {
        header("HTTP/1.1 403 Forbidden");
    }

    $log->appendLog('Someone tried to access page only for admins with permission to view invoices.');

    die('You\'re not allowed to access this page.');
}

$log->appendLog('Starting invoice export.');

// Get export format from plugin's configuration.
// https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/manifest.md#configuration
$configManager = \Ubnt\UcrmPluginSdk\Service\PluginConfigManager::create();
$config = $configManager->loadConfig();
// the "exportFormat" key must be defined in plugin's manifest file, see the link above
$exportFormat = $config['exportFormat'];

// Get UCRM API manager.
$api = \Ubnt\UcrmPluginSdk\Service\UcrmApi::create();

// Load invoices from UCRM API ordered by created date in descending direction.
// https://ucrm.docs.apiary.io/#reference/invoices/invoicesclientidcreateddatefromcreateddateto/get
$invoices = $api->get(
    'invoices',
    [
        'order' => 'createdDate',
        'direction' => 'DESC',
    ]
);

foreach ($invoices as $invoice) {
    // some export implementation, using the $exportFormat
}

// Get plugin's public URL from automatically generated UCRM options.
// https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#ucrmjson
$optionsManager = \Ubnt\UcrmPluginSdk\Service\UcrmOptionsManager::create();
$pluginPublicUrl = $optionsManager->loadOptions()->pluginPublicUrl;

$log->appendLog(sprintf('Finished invoice export. Take a look at %s.', $pluginPublicUrl));
```

## Pack script
To pack your plugin for use in UCRM, you can use the provided pack script. Run this command from the root directory:
```
./vendor/bin/pack-plugin
```

The script will create ZIP archive of the plugin, which can be uploaded to UCRM.
If you are using the directory structure of official [UCRM plugins repository](https://github.com/Ubiquiti-App/UCRM-plugins) the archive will be created one level up, next to your `README.md` file and `src/` directory.
Otherwise it will be created in your root directory.

> If the plugin's root directory is not detected correctly, you can give it to the script as an argument. For example:  
> `./vendor/bin/pack-plugin /home/username/my-new-plugin`

## Tests 
Unit tests can be executed by running this command from the root directory:
```
./vendor/bin/phpunit
```

Static analysis can be executed by running this command from the root directory:
```
./vendor/bin/phpstan analyse src tests --level max
```

Coding standard check can be executed by running this command from the root directory:
```
./vendor/bin/ecs check src tests
```

## Disclaimer 
The software is provided "as is", without any warranty of any kind. Read more in the [licence](https://github.com/Ubiquiti-App/UCRM-Plugin-SDK/blob/master/LICENSE.md).
