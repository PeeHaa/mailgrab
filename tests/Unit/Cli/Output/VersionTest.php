<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Output;

use PeeHaa\MailGrab\Cli\Output\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testRender()
    {
        $this->assertRegExp('~\d+\.\d+\.\d+~', (new Version())->render());
    }
}
