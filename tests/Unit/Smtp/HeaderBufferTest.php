<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp;

use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PHPUnit\Framework\TestCase;

class HeaderBufferTest extends TestCase
{
    public function testKey()
    {
        $this->assertSame('thekey', (new HeaderBuffer('thekey', 'firstchunk'))->getKey());
    }

    public function testGetBufferIsInitializedWithCtorChunk()
    {
        $this->assertSame('firstchunk', (new HeaderBuffer('thekey', 'firstchunk'))->getValue());
    }

    public function testAppend()
    {
        $buffer = new HeaderBuffer('thekey', 'firstchunk');

        $buffer->append('secondchunk');
        $buffer->append('thirdchunk');

        $this->assertSame('firstchunksecondchunkthirdchunk', $buffer->getValue());
    }
}
