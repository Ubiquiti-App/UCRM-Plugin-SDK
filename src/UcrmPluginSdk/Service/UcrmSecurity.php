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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Ubnt\UcrmPluginSdk\Data\UcrmUser;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;
use Ubnt\UcrmPluginSdk\Exception\JsonException;
use Ubnt\UcrmPluginSdk\Util\Helpers;
use Ubnt\UcrmPluginSdk\Util\Json;

/**
 * This class can be used to retrieve User, that is currently logged into UCRM.
 *
 * Note: This feature is available since UCRM 2.14.0-beta1.
 */
class UcrmSecurity
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Creates instance of UcrmSecurity class, using automatically generated plugin configuration to setup UCRM URL.
     *
     * Example usage:
     *
     *    $ucrmSecurity = UcrmSecurity::create();
     *
     * The `$pluginRootPath` parameter can be used to change root directory of plugin.
     *
     * @throws ConfigurationException
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     *
     * @see AbstractOptionsManager::__construct() for more information.
     */
    public static function create(?string $pluginRootPath = null): self
    {
        $ucrmOptionsManager = new UcrmOptionsManager($pluginRootPath);
        $options = $ucrmOptionsManager->loadOptions();

        $ucrmUrl = $options->ucrmLocalUrl ?? $options->ucrmPublicUrl ?? '';
        if ($ucrmUrl === '') {
            throw new ConfigurationException('UCRM URL is missing in plugin configuration.');
        }

        $ucrmUrl = sprintf('%s/', rtrim($ucrmUrl, '/'));

        $client = new Client(
            [
                'base_uri' => $ucrmUrl,
                // If the URL is localhost over HTTPS, do not verify SSL certificate.
                'verify' => ! Helpers::isUrlSecureLocalhost($ucrmUrl),
            ]
        );

        return new self($client);
    }

    /**
     * Returns user, that is currently logged into UCRM or `null`, if there is no logged in user.
     *
     * Note: This feature is available since UCRM 2.14.0-beta1.
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getUser(): ?UcrmUser
    {
        try {
            $cookies = [
                'PHPSESSID=' . $this->getSanitizedCookie('PHPSESSID'),
                'nms-crm-php-session-id=' . $this->getSanitizedCookie('nms-crm-php-session-id'),
                'nms-session=' . $this->getSanitizedCookie('nms-session'),
            ];

            $response = $this->client->request(
                'GET',
                'current-user',
                [
                    'headers' => [
                        'content-type' => 'application/json',
                        'cookie' => implode('; ', $cookies),
                    ],
                ]
            );
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === 403) {
                return null;
            }

            throw $exception;
        }

        return new UcrmUser(Json::decode($response->getBody()->getContents()));
    }

    private function getSanitizedCookie(string $name): ?string
    {
        $value = $_COOKIE[$name] ?? '';
        $value = is_string($value) ? $value : '';

        return preg_replace('~[^a-zA-Z0-9-]~', '', $value);
    }
}
