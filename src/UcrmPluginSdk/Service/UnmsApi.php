<?php
/*
 * This file is part of UCRM Plugin SDK.
 *
 * Copyright (c) 2019 Ubiquiti Networks
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ubnt\UcrmPluginSdk\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;
use Ubnt\UcrmPluginSdk\Exception\JsonException;
use Ubnt\UcrmPluginSdk\Util\Helpers;
use Ubnt\UcrmPluginSdk\Util\Json;

/**
 * This class can be used to call UNMS API.
 *
 * You can find API documentation at https://help.ubnt.com/hc/en-us/articles/115003906007-UCRM-API-Usage
 */
class UnmsApi
{
    private const HEADER_AUTH_TOKEN = 'x-auth-token';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $authToken;

    public function __construct(Client $client, string $authToken)
    {
        $this->client = $client;
        $this->authToken = $authToken;
    }

    /**
     * Creates instance of UnmsApi class, using automatically generated plugin configuration to setup API URL.
     *
     * Example usage:
     *
     *    $unmsApi = UnmsApi::create('unmsAuthToken');
     *
     * The `$pluginRootPath` parameter can be used to change root directory of plugin.
     *
     * @see AbstractOptionsManager::__construct() for more information.
     *
     * @throws ConfigurationException
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     */
    public static function create(string $unmsAuthToken, ?string $pluginRootPath = null): self
    {
        $ucrmOptionsManager = new UcrmOptionsManager($pluginRootPath);
        $options = $ucrmOptionsManager->loadOptions();

        $unmsUrl = $options->unmsLocalUrl ?? '';
        if ($unmsUrl === '') {
            throw new ConfigurationException('UNMS URL is missing in plugin configuration.');
        }

        $unmsApiUrl = sprintf(
            '%s/api/v2.1/',
            rtrim($unmsUrl, '/')
        );

        $client = new Client(
            [
                'base_uri' => $unmsApiUrl,
                // If the URL is localhost over HTTPS, do not verify SSL certificate.
                'verify' => ! Helpers::isUrlSecureLocalhost($unmsApiUrl),
            ]
        );

        return new self($client, $unmsAuthToken);
    }

    /**
     * Sends a GET request to UNMS API.
     *
     * @param mixed[] $query
     *
     * @return mixed[]|string
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function get(string $endpoint, array $query = [])
    {
        $response = $this->request(
            'GET',
            $endpoint,
            [
                'query' => $query,
            ]
        );

        return $this->handleResponse($response);
    }

    /**
     * Sends a POST request to UNMS API.
     *
     * @param mixed[] $data
     *
     * @return mixed[]|string
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function post(string $endpoint, array $data = [])
    {
        $response = $this->request(
            'POST',
            $endpoint,
            [
                'json' => $data,
            ]
        );

        return $this->handleResponse($response);
    }

    /**
     * Sends a PATCH request to UNMS API.
     *
     * @param mixed[] $data
     *
     * @return mixed[]|string
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function patch(string $endpoint, array $data = [])
    {
        $response = $this->request(
            'PATCH',
            $endpoint,
            [
                'json' => $data,
            ]
        );

        return $this->handleResponse($response);
    }

    /**
     * Sends a DELETE request to UNMS API.
     *
     * @throws GuzzleException
     */
    public function delete(string $endpoint): void
    {
        $this->request('DELETE', $endpoint);
    }

    /**
     * @param mixed[] $options
     *
     * @throws GuzzleException
     */
    private function request(string $method, string $endpoint, array $options = []): ResponseInterface
    {
        return $this->client->request(
            $method,
            // strip slash character from beginning of endpoint to make sure base API URL is included correctly
            ltrim($endpoint, '/'),
            array_merge(
                $options,
                [
                    'headers' => [
                        self::HEADER_AUTH_TOKEN => $this->authToken,
                    ],
                ]
            )
        );
    }

    /**
     * @return mixed[]|string
     *
     * @throws JsonException
     */
    private function handleResponse(ResponseInterface $response)
    {
        if (stripos($response->getHeaderLine('content-type'), 'application/json') !== false) {
            return Json::decode($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }
}
