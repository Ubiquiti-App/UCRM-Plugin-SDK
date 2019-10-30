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

namespace Ubnt\UcrmPluginSdk\Service;

use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;
use Ubnt\UcrmPluginSdk\Exception\JsonException;

/**
 * This class can be used to retrieve plugin's configuration from `data/config.json` file.
 *
 * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#dataconfigjson
 */
class PluginConfigManager extends AbstractOptionsManager
{
    private const CONFIG_JSON = 'data/config.json';

    /**
     * @var mixed[]
     */
    private $config = [];

    /**
     * Plugin root path is configured automatically if standard directory structure is used.
     * That is, UCRM Plugin SDK resides in `vendor/ubnt` directory inside of plugin's root.
     *
     * If this is not the case, you can use the `$pluginRootPath` parameter to specify the path.
     */
    public static function create(?string $pluginRootPath = null): self
    {
        return new self($pluginRootPath);
    }

    /**
     * Returns (cached) associative array, which holds plugin's configuration from `data/config.json` file.
     *
     * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#dataconfigjson
     *
     * Example usage:
     *
     *     $pluginConfigManager = new PluginConfigManager();
     *     $config = $pluginConfigManager->loadConfig();
     *     echo $config['yourConfigurationKey'];
     *
     * @return mixed[]
     *
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     */
    public function loadConfig(): array
    {
        if (! $this->config) {
            $this->updateConfig();
        }

        return $this->config;
    }

    /**
     * Refreshes the cached plugin configuration held in this class.
     *
     * Example usage:
     *
     *     $pluginConfigManager = new PluginConfigManager();
     *     $config = $pluginConfigManager->loadConfig();
     *
     *     // ... long operation ...
     *     // ... long operation ...
     *     // ... long operation ...
     *
     *     $pluginConfigManager->updateConfig();
     *     // config is now up to date
     *     $config = $pluginConfigManager->loadConfig();
     *
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     */
    public function updateConfig(): void
    {
        $this->config = $this->getDataFromJson(self::CONFIG_JSON);
    }
}
