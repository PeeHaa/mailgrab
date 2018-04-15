<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Header;

use PeeHaa\MailGrab\Smtp\Header\Buffer;
use PeeHaa\MailGrab\Smtp\Header\Header;
use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function testGetKey()
    {
        $buffer = $this->createMock(Buffer::class);

        $buffer
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('thekey')
        ;

        $buffer
            ->expects($this->once())
            ->method('getBuffer')
            ->willReturn('thevalue')
        ;

        $this->assertSame('thekey', (new Header($buffer))->getKey());
    }

    public function testGetValue()
    {
        $buffer = $this->createMock(Buffer::class);

        $buffer
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('thekey')
        ;

        $buffer
            ->expects($this->once())
            ->method('getBuffer')
            ->willReturn('thevalue')
        ;

        $this->assertSame('thevalue', (new Header($buffer))->getValue());
    }
}
