<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BrunoFontes\Memsource\FetchApi;

final class FetchApiTest extends TestCase
{
    public function testEmptyFetchUrlShouldThrowError()
    {
        $fetch = new FetchApi('fakeToken', 'https://google.com');
        $this->expectExceptionMessage('URL not defined');
        $this->assertNotEmpty($fetch->fetch('get', ''));
    }

    public function testNotEmptyTokenOnFetchShouldNotThrowError()
    {
        $fetch = new FetchApi('fakeToken', 'https://google.com');
        $this->assertNotEmpty($fetch->fetch('get', '/'));
    }
    public function testEmptyTokenOnFetchRawShouldNotThrowError()
    {
        $fetch = new FetchApi(null, 'http://google.com');
        $this->assertNotEmpty($fetch->fetch('raw', '/'));
    }

    public function testEmptyTokenOnFetchGetShouldThrowError()
    {
        $fetch = new FetchApi(null, 'http://testUrl.com');
        $this->expectExceptionMessage('Missing Access Token');
        $fetch->fetch('get', 'url');
    }
    public function testEmptyTokenOnFetchPutShouldThrowError()
    {
        $fetch = new FetchApi(null, 'http://testUrl.com');
        $this->expectExceptionMessage('Missing Access Token');
        $fetch->fetch('put', 'url');
    }
    public function testEmptyTokenOnFetchPostShouldThrowError()
    {
        $fetch = new FetchApi(null, 'http://testUrl.com');
        $this->expectExceptionMessage('Missing Access Token');
        $fetch->fetch('post', 'url');
    }
    public function testEmptyTokenOnFetchJsonPostShouldThrowError()
    {
        $fetch = new FetchApi(null, 'http://testUrl.com');
        $this->expectExceptionMessage('Missing Access Token');
        $fetch->fetch('jsonPost', 'url');
    }
    public function testEmptyTokenOnFetchDownloadShouldThrowError()
    {
        $fetch = new FetchApi(null, 'http://testUrl.com');
        $this->expectExceptionMessage('Missing Access Token');
        $fetch->fetch('download', 'url');
    }
}
