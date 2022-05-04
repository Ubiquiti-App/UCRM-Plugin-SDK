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
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;
use Ubnt\UcrmPluginSdk\Exception\JsonException;
use Ubnt\UcrmPluginSdk\Util\Helpers;
use Ubnt\UcrmPluginSdk\Util\Json;

/**
 * This class can be used to call UCRM API.
 *
 * You can find API documentation at https://help.ubnt.com/hc/en-us/articles/115003906007-UCRM-API-Usage
 */
class UcrmApi
{
    private const HEADER_AUTH_APP_KEY = 'x-auth-app-key';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $appKey;

    public function __construct(Client $client, string $appKey)
    {
        $this->client = $client;
        $this->appKey = $appKey;
    }

    /**
     * Creates instance of UcrmApi class, using automatically generated plugin configuration
     * to setup API URL and App Key.
     *
     * Example usage:
     *
     *    $ucrmApi = UcrmApi::create();
     *
     * The `$pluginRootPath` parameter can be used to change root directory of plugin.
     *
     * @see AbstractOptionsManager::__construct() for more information.
     *
     * @throws ConfigurationException
     * @throws InvalidPluginRootPathException
     * @throws JsonException
     */
    public static function create(?string $pluginRootPath = null): self
    {
        $ucrmOptionsManager = new UcrmOptionsManager($pluginRootPath);
        $options = $ucrmOptionsManager->loadOptions();

        $ucrmUrl = $options->ucrmLocalUrl ?? $options->ucrmPublicUrl ?? '';
        if ($ucrmUrl === '') {
            throw new ConfigurationException('UCRM URL is missing in plugin configuration.');
        }

        $ucrmApiUrl = sprintf(
            '%s/api/v1.0/',
            rtrim($ucrmUrl, '/')
        );

        $client = new Client(
            [
                'base_uri' => $ucrmApiUrl,
                // If the URL is localhost over HTTPS, do not verify SSL certificate.
                'verify' => ! Helpers::isUrlSecureLocalhost($ucrmApiUrl),
            ]
        );

        return new self($client, $options->pluginAppKey);
    }

    /**
     * Sends a GET request to UCRM API.
     *
     * Example usage to get array of clients ordered by ID in descending order:
     *
     *     $clients = $ucrmApi->get(
     *         'clients',
     *         [
     *             'order' => 'client.id',
     *             'direction' => 'DESC'
     *         ]
     *     );
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
     * Sends a POST request to UCRM API.
     *
     * Example usage to create a new client:
     *
     *     $ucrmApi->post(
     *         'clients',
     *         [
     *             'firstName' => 'John',
     *             'lastName' => 'Doe',
     *         ]
     *     );
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
     * Sends a PATCH request to UCRM API.
     *
     * Example usage to change first name of client with ID 42 to James:
     *
     *     $ucrmApi->patch(
     *         'clients/42',
     *         [
     *             'firstName' => 'James',
     *         ]
     *     );
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
     * Sends a DELETE request to UCRM API.
     *
     * Example usage to delete client with ID 42:
     *
     *     $ucrmApi->delete('clients/42');
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
                        self::HEADER_AUTH_APP_KEY => $this->appKey,
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
        $responseBody = $response->getBody()->getContents();
        if (stripos($response->getHeaderLine('content-type'), 'application/json') !== false) {
            return $responseBody !== '' ? Json::decode($responseBody) : $responseBody;
        }

        return $responseBody;
    }
}
