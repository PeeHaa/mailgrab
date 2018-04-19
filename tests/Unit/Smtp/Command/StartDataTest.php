<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\StartData;
use PHPUnit\Framework\TestCase;

class StartDataTest extends TestCase
{
    public function testIsValidReturnsTrueWhenValid()
    {
        $this->assertTrue(StartData::isValid('DATA'));
    }

    public function testIsValidReturnsFalseWhenNotValidLeadingData()
    {
        $this->assertFalse(StartData::isValid('foobarDATA'));
    }

    public function testIsValidReturnsFalseWhenNotValidTrailingData()
    {
        $this->assertFalse(StartData::isValid('DATAfoobar'));
    }
}
