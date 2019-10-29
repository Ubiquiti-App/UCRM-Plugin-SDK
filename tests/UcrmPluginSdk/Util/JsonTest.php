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

use Ubnt\UcrmPluginSdk\Exception\JsonException;

class JsonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param mixed[] $value
     *
     * @dataProvider encodeProvider
     */
    public function testEncode(array $value, bool $exceptionExpected): void
    {
        $exception = null;

        try {
            Json::encode($value);
        } catch (JsonException $exception) {
        }

        if ($exceptionExpected) {
            self::assertInstanceOf(JsonException::class, $exception);
        } else {
            self::assertNull($exception);
        }
    }

    /**
     * @return mixed[]
     */
    public function encodeProvider(): array
    {
        return [
            'valid empty data' => [
                'value' => [],
                'exceptionExpected' => false,
            ],
            'valid filled data' => [
                'value' => [
                    'example' => true,
                    'example2' => 'lorem',
                    'example3' => null,
                ],
                'exceptionExpected' => false,
            ],
            'invalid data' => [
                'value' => [
                    'lorem' => NAN,
                ],
                'exceptionExpected' => true,
            ],
        ];
    }

    /**
     * @dataProvider decodeProvider
     */
    public function testDecode(string $json, bool $exceptionExpected): void
    {
        $exception = null;

        try {
            Json::decode($json);
        } catch (JsonException $exception) {
        }

        if ($exceptionExpected) {
            self::assertInstanceOf(JsonException::class, $exception);
        } else {
            self::assertNull($exception);
        }
    }

    /**
     * @return mixed[]
     */
    public function decodeProvider(): array
    {
        return [
            'valid json' => [
                'json' => '[]',
                'exceptionExpected' => false,
            ],
            'valid json with data' => [
                'json' => '{"example":true,"example2":"lorem","example3":null}',
                'exceptionExpected' => false,
            ],
            'no data' => [
                'json' => '',
                'exceptionExpected' => true,
            ],
            'invalid data' => [
                'json' => '{example:true,example2:lorem,example3:null,}',
                'exceptionExpected' => true,
            ],
        ];
    }
}
