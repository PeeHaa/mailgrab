<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp;

use PeeHaa\MailGrab\Smtp\Header\Header;
use PeeHaa\MailGrab\Smtp\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testRawMessageIsInitializedWithEmptyString()
    {
        $this->assertSame('', (new Message())->getRawMessage());
    }

    public function testRawMessageIsAppendedCorrectly()
    {
        $message = new Message();

        $message->appendToRawMessage('foo');
        $message->appendToRawMessage('bar');

        $this->assertSame("foo\r\nbar\r\n", $message->getRawMessage());
    }

    public function testFromIsCorrectlySet()
    {
        $message = new Message();

        $message->setFrom('foobar');

        $this->assertSame('foobar', $message->getFrom());
    }

    public function testRecipientsIsInitializedWithEmptyArray()
    {
        $this->assertSame([], (new Message())->getRecipients());
    }

    public function testRecipientsAreCorrectlySet()
    {
        $message = new Message();

        $message->addRecipient('foobar@example.com', 'Foo Bar');

        $this->assertCount(1, $message->getRecipients());
        $this->assertSame(['foobar@example.com' => 'Foo Bar'], $message->getRecipients());
    }

    public function testMultipleRecipientsAreCorrectlySet()
    {
        $message = new Message();

        $message->addRecipient('foobar@example.com', 'Foo Bar');
        $message->addRecipient('bazqux@example.com', 'Baz Qux');

        $result = [
            'foobar@example.com' => 'Foo Bar',
            'bazqux@example.com' => 'Baz Qux',
        ];

        $this->assertCount(2, $message->getRecipients());
        $this->assertSame($result, $message->getRecipients());
    }

    public function testHeadersAreInitializedWithEmptyArray()
    {
        $this->assertSame([], (new Message())->getHeaders());
    }

    public function testHeadersAreCorrectlySet()
    {
        $message = new Message();

        $message->addHeader($this->createMock(Header::class));

        $this->assertCount(1, $message->getHeaders());
    }

    public function testMultipleHeadersAreCorrectlySet()
    {
        $message = new Message();

        $header1 = $this->createMock(Header::class);

        $header1
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('foo')
        ;

        $header2 = $this->createMock(Header::class);

        $header2
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('bar')
        ;

        $message->addHeader($header1);
        $message->addHeader($header2);

        $headers = $message->getHeaders();

        $this->assertCount(2, $headers);
        $this->assertTrue(isset($headers['foo']));
        $this->assertTrue(isset($headers['bar']));
    }

    public function testHeaderKeysAreNormalized()
    {
        $message = new Message();

        $header = $this->createMock(Header::class);

        $header
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('FOO')
        ;

        $message->addHeader($header);

        $this->assertTrue(isset($message->getHeaders()['foo']));
    }
}
