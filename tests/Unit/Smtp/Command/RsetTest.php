<?php declare(strict_types=1);

namespace PeeHaa\MailGrabtest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Rset;
use PHPUnit\Framework\TestCase;

class RsetTest extends TestCase
{
    public function testIsValidReturnsTrueWhenValid()
    {
        $this->assertTrue(Rset::isValid('RSET'));
    }

    public function testIsValidReturnsFalseWhenNotValidLeadingData()
    {
        $this->assertFalse(Rset::isValid('foobarRSET'));
    }

    public function testIsValidReturnsTrueWhenWithTrailingData()
    {
        $this->assertTrue(Rset::isValid('RSETfoobar'));
    }
}
