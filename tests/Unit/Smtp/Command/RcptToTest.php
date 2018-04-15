<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\RcptTo;
use PHPUnit\Framework\TestCase;

class RcptToTest extends TestCase
{
    public function testIsValidReturnsTrueOnValidLine()
    {
        $this->assertTrue(RcptTo::isValid('RCPT TO: Foo Bar <foobar@example.com>'));
    }

    public function testIsValidReturnsTrueOnValidLineWithOnlyAddressPart()
    {
        $this->assertTrue(RcptTo::isValid('RCPT TO: <foobar@example.com>'));
    }

    public function testIsValidReturnsTrueWhenThereIsNoSpaceBetweenKeyAndValue()
    {
        $this->assertTrue(RcptTo::isValid('RCPT TO:<foobar@example.com>'));
    }

    public function testIsValidReturnsFalseWhenInvalid()
    {
        $this->assertFalse(RcptTo::isValid('CPT TO: <foobar@example.com>'));
    }

    public function testGetNameWhenNameIsAvailable()
    {
        $this->assertSame('Foo Bar', (new RcptTo('RCPT TO: Foo Bar <foobar@example.com>'))->getName());
    }

    public function testGetNameWhenNameIsNotAvailable()
    {
        $this->assertSame('foobar@example.com', (new RcptTo('RCPT TO:<foobar@example.com>'))->getName());
    }

    public function testGetAddressWhenNameIsAvailable()
    {
        $this->assertSame('foobar@example.com', (new RcptTo('RCPT TO: Foo Bar <foobar@example.com>'))->getAddress());
    }

    public function testGetAddressWhenNameIsNotAvailable()
    {
        $this->assertSame('foobar@example.com', (new RcptTo('RCPT TO:<foobar@example.com>'))->getAddress());
    }
}
