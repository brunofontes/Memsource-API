<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BrunoFontes\Memsource;

final class oauthTest extends TestCase
{
    public function testOauthGetAuthorizationCodeUrl()
    {
        $customServerURL = 'http://myPersonalMemsource.com';
        $api = new Memsource(null, $customServerURL);
        $expected = $customServerURL . '/oauth/authorize?response_type=code&client_id=id&redirect_uri=http%3A%2F%2Furi&scope=openid';
        $memsourceUrl = $api->oauth()->getAuthorizationCodeUrl('id', 'http://uri');
        $this->assertEquals(
            $expected,
            $memsourceUrl
        );
    }

    public function testGetAccessTokenExceptionOnFakeCode()
    {
        $api = new Memsource();
        $this->expectException(\Exception::class);
        $token = $api->oauth()->getAccessToken('fakeCode', 'fakeId', 'fakePass', 'http://any');
    }

    public function testGetAccessTokenExceptionOnEmptyCode()
    {
        $api = new Memsource();
        $this->expectException(\Exception::class);
        $token = $api->oauth()->getAccessToken('', '', '', '');
    }
}
