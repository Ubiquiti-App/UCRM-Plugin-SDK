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

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * This class can be used to manage plugin's log in `data/plugin.log` file.
 *
 * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#datapluginlog
 */
class PluginLogManager
{
    private const PLUGIN_LOG = 'data/plugin.log';

    /**
     * @var string
     */
    private $pluginLogPath;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Plugin root path is configured automatically if standard directory structure is used.
     * That is, UCRM Plugin SDK resides in `vendor/ubnt` directory inside of plugin's root.
     *
     * If this is not the case, you can use the `$pluginRootPath` parameter to specify the path.
     */
    public function __construct(?string $pluginRootPath = null)
    {
        $pluginRootPath = $pluginRootPath ?? __DIR__ . '/../../../../../..';

        $this->pluginLogPath = sprintf('%s/%s', $pluginRootPath, self::PLUGIN_LOG);
        $this->filesystem = new Filesystem();
    }

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
     * Returns content of the plugin's log file (`data/plugin.log`).
     *
     * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#datapluginlog
     *
     * Example usage:
     *
     *     $pluginLogManager = new PluginLogManager();
     *     echo $pluginLogManager->getLog();
     */
    public function getLog(): string
    {
        if (! $this->filesystem->exists($this->pluginLogPath)) {
            return '';
        }

        $log = file_get_contents($this->pluginLogPath);

        return is_string($log) ? $log : '';
    }

    /**
     * Writes message to the plugin's log file (`data/plugin.log`).
     *
     * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#datapluginlog
     *
     * Example usage:
     *
     *     $pluginLogManager = new PluginLogManager();
     *     $pluginLogManager->appendLog('This plugin just did something and wants to let you know about it.');
     *
     * @throws IOException if the file is not writable.
     */
    public function appendLog(string $message): void
    {
        $this->filesystem->appendToFile($this->pluginLogPath, $message . PHP_EOL);
    }

    /**
     * Clears content of the plugin's log file (`data/plugin.log`).
     *
     * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#datapluginlog
     *
     * Example usage:
     *
     *     $pluginLogManager = new PluginLogManager();
     *     $pluginLogManager->clearLog();
     *
     * @throws IOException if the file is not writable.
     */
    public function clearLog(): void
    {
        $this->filesystem->remove($this->pluginLogPath);
    }
}
