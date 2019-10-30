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

class PluginConfigManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testLoadConfig(): void
    {
        $expectedOptions = [
            'token' => 'dummyTokenForTests',
            'startDate' => '1.1.2018',
            'paymentMatchAttribute' => 'invoiceNumber',
            'importUnattached' => '1',
            'lastProcessedPayment' => null,
            'lastProcessedTimestamp' => null,
        ];

        $pluginOptionsManager = PluginConfigManager::create(__DIR__ . '/../../files_enabled');
        $config = $pluginOptionsManager->loadConfig();

        self::assertSame($expectedOptions, $config);
    }

    public function testFileNotFound(): void
    {
        $exception = null;

        try {
            $pluginOptionsManager = PluginConfigManager::create(__DIR__);
            $pluginOptionsManager->loadConfig();
        } catch (InvalidPluginRootPathException $exception) {
        }

        self::assertInstanceOf(InvalidPluginRootPathException::class, $exception);
    }
}
