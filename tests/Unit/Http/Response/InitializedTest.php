<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Response;

use PeeHaa\MailGrab\Http\Response\Initialized;
use PHPUnit\Framework\TestCase;

class InitializedTest extends TestCase
{
    public function testToString()
    {
        $this->assertSame('{"type":"initialized","data":[]}', (string) new Initialized());
    }
}
