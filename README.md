# UCRM Plugin SDK
This repository contains the open source PHP SDK for [UCRM plugins](https://github.com/Ubiquiti-App/UCRM-plugins).

## Installation
The UCRM Plugin SDK can be installed with [Composer](https://getcomposer.org/). Run this command:
```bash
composer require ubnt/ucrm-plugin-sdk
```

## Tests 
Unit tests can be executed by running this command from the root directory:
```bash
./vendor/bin/phpunit
```

Static analysis can be executed by running this command from the root directory:
```bash
./vendor/bin/phpstan analyse src tests --level max
```

Coding standard check can be executed by running this command from the root directory:
```bash
./vendor/bin/ecs check src tests
```

## Disclaimer 
The software is provided "as is", without any warranty of any kind. Read more in the [licence](https://github.com/Ubiquiti-App/UCRM-Plugin-SDK/blob/master/LICENSE).
