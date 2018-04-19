<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("13 foobar\r\n", (string) new Message(13, 'foobar'));
    }
}
