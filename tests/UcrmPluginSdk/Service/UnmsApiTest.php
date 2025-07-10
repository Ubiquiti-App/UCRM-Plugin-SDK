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
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;

class UnmsApiTest extends TestCase
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
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getStatusCode')->willReturn(201);
        $responseMock->method('getBody')->willReturn(Utils::streamFor($returnedBody));
        $responseMock->method('getHeaderLine')->with('content-type')->willReturn($contentType);

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                'clients',
                [
                    'json' => [
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                    ],
                    'headers' => [
                        'x-auth-token' => self::TEST_AUTH_TOKEN,
                    ],
                ]
            )
            ->willReturn($responseMock);

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $result = $ucrmApi->post($endpoint, $data);
        self::assertSame($expectedResult, $result);
    }

    /**
     * @param mixed[]|string $expectedResult
     *
     * @dataProvider responseProvider
     */
    public function testPatch(string $contentType, string $returnedBody, $expectedResult): void
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn(Utils::streamFor($returnedBody));
        $responseMock->method('getHeaderLine')->with('content-type')->willReturn($contentType);

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects(self::once())
            ->method('request')
            ->with(
                'PATCH',
                'clients',
                [
                    'json' => [
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                    ],
                    'headers' => [
                        'x-auth-token' => self::TEST_AUTH_TOKEN,
                    ],
                ]
            )
            ->willReturn($responseMock);

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];
        $result = $ucrmApi->patch($endpoint, $data);
        self::assertSame($expectedResult, $result);
    }

    public function testDelete(): void
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getStatusCode')->willReturn(200);

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects(self::once())
            ->method('request')
            ->with(
                'DELETE',
                'clients',
                [
                    'headers' => [
                        'x-auth-token' => self::TEST_AUTH_TOKEN,
                    ],
                ]
            )
            ->willReturn($responseMock);

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $ucrmApi->delete($endpoint);
    }

    /**
     * @param mixed[]|string $expectedResult
     *
     * @dataProvider responseProvider
     */
    public function testGet(string $contentType, string $returnedBody, $expectedResult): void
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn(Utils::streamFor($returnedBody));
        $responseMock->method('getHeaderLine')->with('content-type')->willReturn($contentType);

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'clients',
                [
                    'query' => [
                        'order' => 'client.id',
                        'direction' => 'DESC',
                    ],
                    'headers' => [
                        'x-auth-token' => self::TEST_AUTH_TOKEN,
                    ],
                ]
            )
            ->willReturn($responseMock);

        $ucrmApi = new UnmsApi($clientMock, self::TEST_AUTH_TOKEN);
        $endpoint = 'clients';
        $query = [
            'order' => 'client.id',
            'direction' => 'DESC',
        ];
        $result = $ucrmApi->get($endpoint, $query);
        self::assertSame($expectedResult, $result);
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
