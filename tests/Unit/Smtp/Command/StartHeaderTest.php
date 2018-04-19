<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\StartHeader;
use PHPUnit\Framework\TestCase;

class StartHeaderTest extends TestCase
{
    public function testIsValidReturnsTrueOnValidHeader()
    {
        $this->assertTrue(StartHeader::isValid('key: value'));
    }

    public function testIsValidReturnsTrueOnValidHeaderCaseInsensitive()
    {
        $this->assertTrue(StartHeader::isValid('KEY: VALUE'));
    }

    public function testIsValidReturnsTrueOnValidHeaderWithDashInKey()
    {
        $this->assertTrue(StartHeader::isValid('Key-Item: VALUE'));
    }

    public function testIsValidReturnsTrueOnValidWithoutWhitespaceBetweenKeyAndValue()
    {
        $this->assertTrue(StartHeader::isValid('key:value'));
    }

    public function testIsValidReturnsFalseOnInvalidCharacterInKey()
    {
        $this->assertFalse(StartHeader::isValid('ke+y:value'));
    }

    public function testGetKey()
    {
        $this->assertSame('key', (new StartHeader('key: value'))->getKey());
    }

    public function testGetValue()
    {
        $this->assertSame('value', (new StartHeader('key: value'))->getValue());
    }
}
