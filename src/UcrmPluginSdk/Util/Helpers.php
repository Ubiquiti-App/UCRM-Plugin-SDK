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

namespace Ubnt\UcrmPluginSdk\Util;

class Helpers
{
    public static function isUrlSecureLocalhost(string $url): bool
    {
        $parsed = parse_url($url);

        return $parsed
            && strtolower($parsed['host'] ?? '') === 'localhost'
            && strtolower($parsed['scheme'] ?? '') === 'https';
    }
}
