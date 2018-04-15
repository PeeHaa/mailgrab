<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Header;

use PeeHaa\MailGrab\Smtp\Header\Buffer;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testKey()
    {
        $this->assertSame('thekey', (new Buffer('thekey'))->getKey());
    }

    public function testGetBufferIsInitializedWithEmptyString()
    {
        $this->assertSame('', (new Buffer('thekey'))->getBuffer());
    }

    public function testAppend()
    {
        $buffer = new Buffer('thekey');

        $buffer->append('firstchunk');
        $buffer->append('secondchunk');

        $this->assertSame('firstchunksecondchunk', $buffer->getBuffer());
    }
}
