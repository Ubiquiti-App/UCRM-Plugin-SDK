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

use Eloquent\Phony\Phpunit\Phony;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;

class UcrmApiTest extends \PHPUnit\Framework\TestCase
{
    private const TEST_APP_KEY = 'testAppKey/xyz';

    public function testCreate(): void
    {
        $exception = null;

        try {
            UcrmApi::create(__DIR__ . '/../../files_enabled');
        } catch (ConfigurationException | InvalidPluginRootPathException $exception) {
        }

        self::assertNull($exception);
    }

    public function testCreateWrongPath(): void
    {
        $exception = null;

        try {
            UcrmApi::create(__DIR__);
        } catch (InvalidPluginRootPathException $exception) {
        }

        self::assertInstanceOf(InvalidPluginRootPathException::class, $exception);
    }

    public function testDisabledPlugin(): void
    {
        $exception = null;

        try {
            UcrmApi::create(__DIR__ . '/../../files_disabled');
        } catch (ConfigurationException $exception) {
        }

        self::assertInstanceOf(ConfigurationException::class, $exception);
    }

    public function testPost(): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(201);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UcrmApi($clientMock, self::TEST_APP_KEY);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $ucrmApi->post($endpoint, $data);

        $clientHandle->request->calledWith(
            'POST',
            $endpoint,
            [
                'json' => $data,
                'headers' => [
                    'x-auth-app-key' => self::TEST_APP_KEY,
                ],
            ]
        );
    }

    public function testPatch(): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(200);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UcrmApi($clientMock, self::TEST_APP_KEY);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $ucrmApi->patch($endpoint, $data);

        $clientHandle->request->calledWith(
            'PATCH',
            $endpoint,
            [
                'json' => $data,
                'headers' => [
                    'x-auth-app-key' => self::TEST_APP_KEY,
                ],
            ]
        );
    }

    public function testDelete(): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(200);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UcrmApi($clientMock, self::TEST_APP_KEY);
        $endpoint = 'clients';
        $ucrmApi->delete($endpoint);

        $clientHandle->request->calledWith(
            'DELETE',
            $endpoint,
            [
                'headers' => [
                    'x-auth-app-key' => self::TEST_APP_KEY,
                ],
            ]
        );
    }

    public function testGet(): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(201);
        $responseHandle->getBody->returns('[]');
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UcrmApi($clientMock, self::TEST_APP_KEY);
        $endpoint = 'clients';
        $query = [
            'order' => 'client.id',
            'direction' => 'DESC',
        ];
        $ucrmApi->get($endpoint, $query);

        $clientHandle->request->calledWith(
            'GET',
            $endpoint,
            [
                'query' => $query,
                'headers' => [
                    'x-auth-app-key' => self::TEST_APP_KEY,
                ],
            ]
        );
    }
}
