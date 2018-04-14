<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Storage;

use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Memory;
use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{
    public function testMailGetsAdded()
    {
        $memory = new Memory();

        $mailMock = $this->createMock(Mail::class);

        $mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('13')
        ;

        $memory->add($mailMock);

        $this->assertSame($mailMock, $memory->get('13'));
    }

    public function testHasWhenAvailable()
    {
        $memory = new Memory();

        $mailMock = $this->createMock(Mail::class);

        $mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('13')
        ;

        $memory->add($mailMock);

        $this->assertTrue($memory->has('13'));
    }

    public function testHasWhenNotAvailable()
    {
        $memory = new Memory();

        $this->assertFalse($memory->has('13'));
    }

    public function testHasWhenNotDeleted()
    {
        $memory = new Memory();

        $mailMock = $this->createMock(Mail::class);

        $mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('13')
        ;

        $memory->add($mailMock);
        $memory->delete('13');

        $this->assertFalse($memory->has('13'));
    }

    public function testIterator()
    {
        $memory = new Memory();

        $mailMocks = [
            $this->createMock(Mail::class),
            $this->createMock(Mail::class),
        ];

        $mailMocks[0]
            ->expects($this->once())
            ->method('getId')
            ->willReturn('13')
        ;

        $mailMocks[1]
            ->expects($this->once())
            ->method('getId')
            ->willReturn('14')
        ;

        $memory->add($mailMocks[0]);
        $memory->add($mailMocks[1]);

        $i = 0;

        foreach ($memory as $id => $mail) {
            $this->assertSame($i + 13, $id);
            $this->assertSame($mailMocks[$i], $mail);

            $i++;
        }
    }

    public function current(): Mail
    {
        return current($this->mails);
    }

    public function next(): void
    {
        next($this->mails);
    }

    public function key(): string
    {
        return key($this->mails);
    }

    public function valid(): bool
    {
        return key($this->mails) !== null;
    }

    public function rewind()
    {
        reset($this->mails);
    }
}
