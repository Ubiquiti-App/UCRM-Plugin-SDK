<?php
/*
 * This file is part of UCRM Plugin SDK.
 *
 * Copyright (c) 2018 Ubiquiti Networks
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ubnt\UcrmPluginSdk\Service;

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

        $pluginOptionsManager = new PluginConfigManager(__DIR__ . '/../../files');
        $config = $pluginOptionsManager->loadConfig();

        self::assertSame($expectedOptions, $config);
    }
}
