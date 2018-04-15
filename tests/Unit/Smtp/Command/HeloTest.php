<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Helo;
use PHPUnit\Framework\TestCase;

class HeloTest extends TestCase
{
    public function testIsValidReturnTrueWhenValid()
    {
        $this->assertTrue(Helo::isValid('HELO foobar'));
    }

    public function testIsValidReturnsFalseWhenNotValid()
    {
        $this->assertFalse(Helo::isValid('ELO foobar'));
    }

    public function testGetAddress()
    {
        $this->assertSame('foobar', (new Helo('HELO foobar'))->getAddress());
    }
}
