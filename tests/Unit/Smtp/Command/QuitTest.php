<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Quit;
use PHPUnit\Framework\TestCase;

class QuitTest extends TestCase
{
    public function testIsValidReturnsTrueWhenValid()
    {
        $this->assertTrue(Quit::isValid('QUIT'));
    }

    public function testIsValidReturnsFalseWhenNotValidLeadingData()
    {
        $this->assertFalse(Quit::isValid('foobarQUIT'));
    }

    public function testIsValidReturnsFalseWhenNotValidTrailingData()
    {
        $this->assertFalse(Quit::isValid('QUITfoobar'));
    }
}
