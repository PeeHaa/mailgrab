<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\ClosingTransmission;
use PHPUnit\Framework\TestCase;

class ClosingTransmissionTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("221 foobar\r\n", (string) new ClosingTransmission('foobar'));
    }
}
