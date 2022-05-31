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

use Eloquent\Phony\Phpunit\Phony;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;

class UnmsApiTest extends \PHPUnit\Framework\TestCase
{
    private const TEST_AUTH_TOKEN = 'test-auth-token';

    public function testCreate(): void
    {
        $exception = null;

        try {
            UnmsApi::create(self::TEST_AUTH_TOKEN, __DIR__ . '/../../files_enabled');
        } catch (ConfigurationException | InvalidPluginRootPathException $exception) {
        }

        self::assertNull($exception);
    }

    public function testCreateWrongPath(): void
    {
        $exception = null;

        try {
            UnmsApi::create(self::TEST_AUTH_TOKEN, __DIR__);
        } catch (InvalidPluginRootPathException $exception) {
        }

        self::assertInstanceOf(InvalidPluginRootPathException::class, $exception);
    }

    public function testDisabledPlugin(): void
    {
        $exception = null;

        try {
            UnmsApi::create(self::TEST_AUTH_TOKEN, __DIR__ . '/../../files_disabled');
        } catch (ConfigurationException $exception) {
        }

        self::assertInstanceOf(ConfigurationException::class, $exception);
    }

    /**
     * @param mixed[]|string $expectedResult
     *
     * @dataProvider responseProvider
     */
    public function testPost(string $contentType, string $returnedBody, $expectedResult): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(201);
        $responseHandle->getBody->returns(Utils::streamFor($returnedBody));
        $responseHandle->getHeaderLine->with('content-type')->returns($contentType);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $result = $ucrmApi->post($endpoint, $data);
        self::assertSame($expectedResult, $result);

        $clientHandle->request->calledWith(
            'POST',
            $endpoint,
            [
                'json' => $data,
                'headers' => [
                    'x-auth-token' => self::TEST_AUTH_TOKEN,
                ],
            ]
        );
    }

    /**
     * @param mixed[]|string $expectedResult
     *
     * @dataProvider responseProvider
     */
    public function testPatch(string $contentType, string $returnedBody, $expectedResult): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(200);
        $responseHandle->getBody->returns(Utils::streamFor($returnedBody));
        $responseHandle->getHeaderLine->with('content-type')->returns($contentType);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $result = $ucrmApi->patch($endpoint, $data);
        self::assertSame($expectedResult, $result);

        $clientHandle->request->calledWith(
            'PATCH',
            $endpoint,
            [
                'json' => $data,
                'headers' => [
                    'x-auth-token' => self::TEST_AUTH_TOKEN,
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

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $ucrmApi->delete($endpoint);

        $clientHandle->request->calledWith(
            'DELETE',
            $endpoint,
            [
                'headers' => [
                    'x-auth-token' => self::TEST_AUTH_TOKEN,
                ],
            ]
        );
    }

    /**
     * @param mixed[]|string $expectedResult
     *
     * @dataProvider responseProvider
     */
    public function testGet(string $contentType, string $returnedBody, $expectedResult): void
    {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns(200);
        $responseHandle->getBody->returns(Utils::streamFor($returnedBody));
        $responseHandle->getHeaderLine->with('content-type')->returns($contentType);
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        $clientHandle->request->returns($responseMock);
        $clientMock = $clientHandle->get();

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $query = [
            'order' => 'client.id',
            'direction' => 'DESC',
        ];
        $result = $ucrmApi->get($endpoint, $query);
        self::assertSame($expectedResult, $result);

        $clientHandle->request->calledWith(
            'GET',
            $endpoint,
            [
                'query' => $query,
                'headers' => [
                    'x-auth-token' => self::TEST_AUTH_TOKEN,
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    public function responseProvider(): array
    {
        return [
            [
                'contentType' => 'text/plain',
                'returnedBody' => 'lorem ipsum dolor',
                'expectedResult' => 'lorem ipsum dolor',
            ],
            [
                'contentType' => 'application/json',
                'returnedBody' => '["lorem", "ipsum"]',
                'expectedResult' => ['lorem', 'ipsum'],
            ],
        ];
    }
}
