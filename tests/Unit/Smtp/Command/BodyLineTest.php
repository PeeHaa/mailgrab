<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\BodyLine;
use PHPUnit\Framework\TestCase;

class BodyLineTest extends TestCase
{
    public function testIsValid()
    {
        $this->assertTrue(BodyLine::isValid('foobar'));
    }

    public function testgetLine()
    {
        $this->assertSame('foobar', (new BodyLine('foobar'))->getLine());
    }
}
