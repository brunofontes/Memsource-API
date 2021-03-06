<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BrunoFontes\Memsource;

final class JobsTest extends TestCase
{
    public function testInvalidProjectUidShouldThrowError()
    {
        $api = new Memsource('fakeToken');
        $this->expectException(\Exception::class);
        $api->jobs()->list('invalidProjectUid', []);
    }

    public function testDownloadTargetFileReturnsNull()
    {
        $api = new Memsource('fakeToken');
        $this->assertEquals(
            null,
            $api->jobs()->downloadTargetFile('projUid', 'jobUid', 'filename.xliff')
        );
    }

    public function testDownloadOriginalFileReturnsNull()
    {
        $api = new Memsource('fakeToken');
        $this->assertEquals(
            null,
            $api->jobs()->downloadOriginalFile('projUid', 'jobUid', 'filename.xliff')
        );
    }
}
