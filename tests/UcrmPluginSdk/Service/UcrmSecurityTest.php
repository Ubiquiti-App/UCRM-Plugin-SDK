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
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Ubnt\UcrmPluginSdk\Data\UcrmUser;
use Ubnt\UcrmPluginSdk\Exception\ConfigurationException;
use Ubnt\UcrmPluginSdk\Exception\InvalidPluginRootPathException;

class UcrmSecurityTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $exception = null;

        try {
            UcrmSecurity::create(__DIR__ . '/../../files_enabled');
        } catch (ConfigurationException | InvalidPluginRootPathException $exception) {
        }

        self::assertNull($exception);
    }

    public function testCreateWrongPath(): void
    {
        $exception = null;

        try {
            UcrmSecurity::create(__DIR__);
        } catch (InvalidPluginRootPathException $exception) {
        }

        self::assertInstanceOf(InvalidPluginRootPathException::class, $exception);
    }

    public function testDisabledPlugin(): void
    {
        $exception = null;

        try {
            UcrmSecurity::create(__DIR__ . '/../../files_disabled');
        } catch (ConfigurationException $exception) {
        }

        self::assertInstanceOf(ConfigurationException::class, $exception);
    }

    /**
     * @dataProvider getUserDataProvider
     */
    public function testGetUser(
        string $responseBody,
        int $responseCode,
        bool $expectedIsClient,
        bool $expectedPermissionsFilled
    ): void {
        $responseHandle = Phony::mock(Response::class);
        $responseHandle->getStatusCode->returns($responseCode);
        $responseHandle->getBody->returns(Utils::streamFor($responseBody));
        $responseMock = $responseHandle->get();

        $clientHandle = Phony::mock(Client::class);
        if ($responseCode !== 200) {
            $clientHandle->request->throws(new ClientException('', Phony::mock(Request::class)->get(), $responseMock));
        } else {
            $clientHandle->request->returns($responseMock);
        }
        $clientMock = $clientHandle->get();

        $ucrmSecurity = new UcrmSecurity($clientMock);

        try {
            $_COOKIE['PHPSESSID'] = [
                'test' => 'wrong data in cookie is handled without error',
            ];
            $user = $ucrmSecurity->getUser();
        } catch (GuzzleException $exception) {
            self::assertSame($responseCode, $exception->getCode());

            return;
        }

        if ($responseCode === 403) {
            self::assertNull($user);

            return;
        }

        assert($user instanceof UcrmUser);
        self::assertSame($expectedIsClient, $user->isClient);

        if ($expectedPermissionsFilled) {
            self::assertNotEmpty($user->permissions);
            self::assertNotEmpty($user->specialPermissions);
        } else {
            self::assertEmpty($user->permissions);
            self::assertEmpty($user->specialPermissions);
        }
    }

    /**
     * @return mixed[]
     */
    public function getUserDataProvider(): array
    {
        return [
            'admin' => [
                'responseBody' => '{"userId":1,"username":"admin","isClient":false,"clientId":null,"userGroup":"Admin Group","specialPermissions":{"special\/view_financial_information":"allow","special\/client_export":"allow","special\/job_comment_edit_delete":"allow","special\/show_device_passwords":"allow","special\/client_log_edit_delete":"allow","special\/clients_financial_information":"allow","special\/client_impersonation":"allow"},"permissions":{"clients\/clients":"edit","network\/devices":"edit","network\/device_interfaces":"edit","billing\/invoices":"edit","system\/billing\/organization_bank_accounts":"edit","system\/organizations":"edit","billing\/payments":"edit","system\/items\/products":"edit","clients\/services":"edit","system\/other\/reasons_for_suspending_service":"edit","system\/settings":"edit","network\/sites":"edit","system\/items\/surcharges":"edit","system\/items\/service_plans":"edit","system\/billing\/taxes":"edit","system\/security\/users":"edit","system\/security\/groups_and_permissions":"edit","system\/other\/vendors":"edit","reports\/invoiced_revenue":"edit","reports\/taxes":"edit","system\/logs\/device_log":"edit","system\/logs\/email_log":"edit","system\/logs\/system_log":"edit","system\/tools\/backup":"edit","billing\/refunds":"edit","system\/tools\/ssl_certificate":"edit","system\/other\/sandbox_termination":"edit","network\/outages":"edit","network\/unknown_devices":"edit","system\/security\/app_keys":"edit","clients\/documents":"edit","system\/tools\/webroot":"edit","system\/billing\/invoicing":"edit","system\/billing\/suspension":"edit","system\/tools\/downloads":"edit","network\/network_map":"edit","system\/customization\/invoice_templates":"edit","system\/tools\/fcc_reports":"edit","system\/other\/custom_attributes":"edit","scheduling\/all_jobs":"edit","scheduling\/my_jobs":"edit","system\/other\/client_tags":"edit","system\/tools\/updates":"edit","ticketing\/ticketing":"edit","system\/billing\/fees":"edit","system\/tools\/mailing":"edit","reports\/data_usage":"edit","system\/other\/contact_types":"edit","system\/customization\/notification_settings":"edit","system\/customization\/suspension_templates":"edit","system\/customization\/email_templates":"edit","system\/customization\/appearance":"edit","system\/customization\/quote_templates":"edit","billing\/quotes":"edit","system\/plugins":"edit","system\/webhooks":"edit","system\/customization\/payment_receipt_templates":"edit","system\/customization\/client_zone_pages":"edit","system\/tools\/client_import":"edit","system\/tools\/payment_import":"edit","system\/customization\/account_statement_templates":"edit"},"locale":"en_US"}',
                'responseCode' => 200,
                'expectedIsClient' => false,
                'expectedPermissionsFilled' => true,
            ],
            'client' => [
                'responseBody' => '{"userId":1255,"username":"client@example.com","isClient":true,"clientId":256,"userGroup":null,"specialPermissions":null,"permissions":null,"locale":"en_US"}',
                'responseCode' => 200,
                'expectedIsClient' => true,
                'expectedPermissionsFilled' => false,
            ],
            'anonymous' => [
                'responseBody' => '',
                'responseCode' => 403,
                'expectedIsClient' => false,
                'expectedPermissionsFilled' => false,
            ],
            'not-found' => [
                'responseBody' => '',
                'responseCode' => 404,
                'expectedIsClient' => false,
                'expectedPermissionsFilled' => false,
            ],
        ];
    }
}
