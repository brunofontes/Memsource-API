<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BrunoFontes\Memsource;

final class BilingualFileTest extends TestCase
{
    public function testEmptyDownloadJobUidListShouldReturnEmptyFilenames()
    {
        $api = new Memsource('fakeToken');
        $this->assertEquals(
            [],
            $api->bilingualFile()->download('uid', [], 'filename.xliff')
        );
    }
    public function testInvalidDownloadUidsShouldThrowError()
    {
        $api = new Memsource('fakeToken');
        $this->expectException(\Exception::class);
        $api->bilingualFile()->download('uid', ['a'], 'filename.xliff');
    }

    public function testUploadInexistentFileShouldThrowError()
    {
        $api = new Memsource('fakeToken');
        $this->expectException(\Exception::class);
        $api->bilingualFile()->upload('myInvalidFile', []);
    }

    public function testUploadWithNoTokenShouldThrowError()
    {
        $api = new Memsource('fakeToken');
        $this->expectException(\Exception::class);
        $api->bilingualFile()->upload('tests/bilingualFileTest.php', []);
    }
}
