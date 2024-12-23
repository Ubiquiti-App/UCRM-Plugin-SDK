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

namespace Ubnt\UcrmPluginSdk\Data;

/**
 * This data class holds automatically generated UCRM options from `ucrm.json` file.
 * You can use UcrmOptionsManager class to get it.
 *
 * @see \Ubnt\UcrmPluginSdk\Service\UcrmOptionsManager
 * @see https://github.com/Ubiquiti-App/UCRM-plugins/blob/master/docs/file-structure.md#ucrmjson
 */
class UcrmOptions
{
    public ?string $pluginAppKey;

    public ?string $pluginPublicUrl;

    public ?int $pluginId;

    public ?string $ucrmPublicUrl;

    public ?string $ucrmLocalUrl;

    public ?string $unmsLocalUrl;
}
