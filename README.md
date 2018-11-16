# UCRM Plugin SDK
[![Build Status](https://img.shields.io/travis/com/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://travis-ci.com/Ubiquiti-App/UCRM-Plugin-SDK)
[![Latest Release](https://img.shields.io/github/release/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://packagist.org/packages/ubnt/ucrm-plugin-sdk)
[![License](https://img.shields.io/github/license/Ubiquiti-App/UCRM-Plugin-SDK.svg)](https://packagist.org/packages/ubnt/ucrm-plugin-sdk)

This repository contains the open source PHP SDK for [UCRM plugins](https://github.com/Ubiquiti-App/UCRM-plugins).

## Installation
The UCRM Plugin SDK can be installed with [Composer](https://getcomposer.org/). Run this command:
```
composer require ubnt/ucrm-plugin-sdk
```

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
The software is provided "as is", without any warranty of any kind. Read more in the [licence](https://github.com/Ubiquiti-App/UCRM-Plugin-SDK/blob/master/LICENSE).
