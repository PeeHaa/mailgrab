<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\EndBody;
use PHPUnit\Framework\TestCase;

class EndBodyTest extends TestCase
{
    public function testIsValidReturnTrueWhenValid()
    {
        $this->assertTrue(EndBody::isValid('.'));
    }

    public function testIsValidReturnsFalseWhenNotValidLeadingData()
    {
        $this->assertFalse(EndBody::isValid('foobar.'));
    }

    public function testIsValidReturnsFalseWhenNotValidTrailingData()
    {
        $this->assertFalse(EndBody::isValid('.foobar'));
    }
}
