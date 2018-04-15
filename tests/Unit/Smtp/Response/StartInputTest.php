<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\StartInput;
use PHPUnit\Framework\TestCase;

class StartInputTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("354 foobar\r\n", (string) new StartInput('foobar'));
    }
}
