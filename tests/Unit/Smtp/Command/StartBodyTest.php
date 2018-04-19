<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\StartBody;
use PHPUnit\Framework\TestCase;

class StartBodyTest extends TestCase
{
    public function testIsValidReturnsTrueWhenValid()
    {
        $this->assertTrue(StartBody::isValid(''));
    }

    public function testIsValidReturnsFalseWhenNotEmpty()
    {
        $this->assertFalse(StartBody::isValid('foobar'));
    }
}
