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

use Ubnt\UcrmPluginSdk\Data\UcrmOptions;

class UcrmOptionsManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testLoadOptions(): void
    {
        $pluginOptionsManager = new UcrmOptionsManager(__DIR__ . '/../../files');
        $options = $pluginOptionsManager->loadOptions();

        self::assertInstanceOf(UcrmOptions::class, $options);
        self::assertSame('https://ucrm-demo.ubnt.com/', $options->ucrmPublicUrl);
        self::assertSame('http://localhost/', $options->ucrmLocalUrl);
        self::assertNull($options->pluginPublicUrl);
        self::assertSame('MyePrzJ3gqJ3rs3RW4B4saP1CyYgPcEpRdHl4htO3lEIX4mBJq0vbUyGYNd99VXt', $options->pluginAppKey);
    }
}
