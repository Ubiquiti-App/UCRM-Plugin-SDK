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

use Ubnt\UcrmPluginSdk\Data\UcrmOptions;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;

class UcrmOptionsManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testLoadOptions(): void
    {
        $pluginOptionsManager = UcrmOptionsManager::create(__DIR__ . '/../../files_enabled');
        $options = $pluginOptionsManager->loadOptions();

        self::assertInstanceOf(UcrmOptions::class, $options);
        self::assertSame('https://ucrm-demo.ubnt.com/', $options->ucrmPublicUrl);
        self::assertSame('http://localhost/', $options->ucrmLocalUrl);
        self::assertSame('http://unms:8081/', $options->unmsLocalUrl);
        self::assertNull($options->pluginPublicUrl);
        self::assertSame('MyePrzJ3gqJ3rs3RW4B4saP1CyYgPcEpRdHl4htO3lEIX4mBJq0vbUyGYNd99VXt', $options->pluginAppKey);
        self::assertSame(123, $options->pluginId);
    }

    public function testFileNotFound(): void
    {
        $exception = null;

        try {
            $pluginOptionsManager = UcrmOptionsManager::create(__DIR__);
            $pluginOptionsManager->loadOptions();
        } catch (InvalidPluginRootPathException $exception) {
        }

        self::assertInstanceOf(InvalidPluginRootPathException::class, $exception);
    }
}
