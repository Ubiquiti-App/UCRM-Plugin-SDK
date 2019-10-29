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

class PluginLogManagerTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        @unlink(__DIR__ . '/../../files_enabled/data/plugin.log');
    }

    public function testLogOperations(): void
    {
        $pluginLogManager = PluginLogManager::create(__DIR__ . '/../../files_enabled');
        self::assertSame('', $pluginLogManager->getLog());

        $pluginLogManager->appendLog('This is first test message.');
        $pluginLogManager->appendLog('This is second test message.');

        self::assertSame(
            implode(
                '',
                [
                    'This is first test message.' . PHP_EOL,
                    'This is second test message.' . PHP_EOL,
                ]
            ),
            $pluginLogManager->getLog()
        );

        $pluginLogManager->clearLog();
        self::assertSame('', $pluginLogManager->getLog());
    }
}
