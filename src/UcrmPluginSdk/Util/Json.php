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

class Json
{
    /**
     * @param mixed[] $value
     *
     * @throws JsonException
     */
    public static function encode(array $value): string
    {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
        $error = json_last_error();
        if ($json === false || $error !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), $error);
        }

        return $json;
    }

    /**
     * @return mixed[]
     *
     * @throws JsonException
     */
    public static function decode(string $json): array
    {
        $value = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), $error);
        }

        return $value;
    }
}
