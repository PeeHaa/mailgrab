<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Ehlo;
use PHPUnit\Framework\TestCase;

class EhloTest extends TestCase
{
    public function testIsValidReturnTrueWhenValid()
    {
        $this->assertTrue(Ehlo::isValid('EHLO foobar'));
    }

    public function testIsValidReturnsFalseWhenNotValid()
    {
        $this->assertFalse(Ehlo::isValid('HLO foobar'));
    }

    public function testGetAddress()
    {
        $this->assertSame('foobar', (new Ehlo('EHLO foobar'))->getAddress());
    }
}
