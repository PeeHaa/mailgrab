<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Output;

use PeeHaa\MailGrab\Cli\Output\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testRenderGitVersion()
    {
        $this->assertRegExp('~\d+\.\d+\.\d+~', (new Version(__DIR__ . '/../../../..'))->render());
    }

    public function testRenderBuildVersion()
    {
        $this->assertSame('120.13.99', (new Version(DATA_DIR))->render());
    }
}
