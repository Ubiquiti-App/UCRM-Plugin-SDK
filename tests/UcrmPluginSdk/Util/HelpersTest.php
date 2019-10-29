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

namespace Ubnt\UcrmPluginSdk\Util;

class HelpersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider isUrlSecureLocalhostProvider
     */
    public function testIsUrlSecureLocalhost(string $url, bool $expected): void
    {
        self::assertSame($expected, Helpers::isUrlSecureLocalhost($url));
    }

    /**
     * @return mixed[]
     */
    public function isUrlSecureLocalhostProvider(): array
    {
        return [
            'secure localhost API URL' => [
                'url' => 'https://localhost/api/v1.0/',
                'expected' => true,
            ],
            'insecure localhost API URL' => [
                'url' => 'http://localhost/api/v1.0/',
                'expected' => false,
            ],
            'secure localhost UCRM URL' => [
                'url' => 'https://localhost/',
                'expected' => true,
            ],
            'insecure localhost UCRM URL' => [
                'url' => 'http://localhost/',
                'expected' => false,
            ],
            'secure foreign URL' => [
                'url' => 'https://www.example.com/',
                'expected' => false,
            ],
            'insecure foreign URL' => [
                'url' => 'http://www.example.com/',
                'expected' => false,
            ],
        ];
    }
}
