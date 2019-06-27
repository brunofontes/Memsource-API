<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BrunoFontes\Memsource;

final class ProjectTest extends TestCase
{
    public function testNoTokenShouldThrowError()
    {
        $api = new Memsource();
        $this->expectExceptionMessage('Missing Access Token');
        $api->project()->list();
    }

    public function testInvalidProjectUidShouldThrowError()
    {
        $api = new Memsource('fakeToken');
        $this->expectException(\Exception::class);
        $api->project()->get('invalidProjectUid');
    }
}
