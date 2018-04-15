<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\ServiceReady;
use PHPUnit\Framework\TestCase;

class ServiceReadyTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("220 foobar\r\n", (string) new ServiceReady('foobar'));
    }
}
