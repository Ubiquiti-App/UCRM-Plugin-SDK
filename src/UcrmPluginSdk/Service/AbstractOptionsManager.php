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
use Ubnt\UcrmPluginSdk\Util\Json;

abstract class AbstractOptionsManager
{
    /**
     * @var string
     */
    private $pluginRootPath;

    /**
     * Plugin root path is configured automatically if standard directory structure is used.
     * That is, UCRM Plugin SDK resides in `vendor/ubnt` directory inside of plugin's root.
     *
     * If this is not the case, you can use the `$pluginRootPath` parameter to specify the path.
     */
    public function __construct(?string $pluginRootPath = null)
    {
        $this->pluginRootPath = $pluginRootPath ?? __DIR__ . '/../../../../../..';
    }

    /**
     * @return mixed[]
     *
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     */
    protected function getDataFromJson(string $filename): array
    {
        $path = sprintf('%s/%s', rtrim($this->pluginRootPath, '/'), $filename);
        if (! file_exists($path)) {
            throw new InvalidPluginRootPathException(
                sprintf(
                    'Could not find file "%s" in "%s" directory.',
                    $filename,
                    $this->pluginRootPath
                )
            );
        }

        $json = file_get_contents($path);
        return Json::decode($json !== false ? $json : '[]');
    }
}
