<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\MailFrom;
use PHPUnit\Framework\TestCase;

class MailFromTest extends TestCase
{
    public function testIsValidReturnsTrueOnValidLine()
    {
        $this->assertTrue(MailFrom::isValid('MAIL FROM: <foobar@example.com> Foo Bar'));
    }

    public function testIsValidReturnsTrueOnValidLineWithOnlyAddressPart()
    {
        $this->assertTrue(MailFrom::isValid('MAIL FROM: <foobar@example.com>'));
    }

    public function testIsValidReturnsTrueWhenThereIsNoSpaceBetweenKeyAndValue()
    {
        $this->assertTrue(MailFrom::isValid('MAIL FROM:<foobar@example.com>'));
    }

    public function testIsValidReturnsFalseWhenInvalid()
    {
        $this->assertFalse(MailFrom::isValid('AIL FROM:<foobar@example.com>'));
    }

    public function testGetAddressReturnsAddressWhenAlsoNameIsAvailable()
    {
        $this->assertSame('foobar@example.com', (new MailFrom('MAIL FROM: <foobar@example.com> Foo Bar'))->getAddress());
    }

    public function testGetAddressReturnsAddressWhenOnlyAddressIsAvailable()
    {
        $this->assertSame('foobar@example.com', (new MailFrom('MAIL FROM: <foobar@example.com>'))->getAddress());
    }

    public function testGetAddressReturnsAddressWhenThereIsNoSpaceBetweenKeyAndValue()
    {
        $this->assertSame('foobar@example.com', (new MailFrom('MAIL FROM:<foobar@example.com>'))->getAddress());
    }
}
