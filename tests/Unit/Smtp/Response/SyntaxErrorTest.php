<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\SyntaxError;
use PHPUnit\Framework\TestCase;

class SyntaxErrorTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("500 foobar\r\n", (string) new SyntaxError('foobar'));
    }
}
