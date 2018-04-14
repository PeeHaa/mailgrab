<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Response;

use PeeHaa\MailGrab\Http\Response\MailInfo;
use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PeeHaa\MailGrab\Smtp\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MailInfoTest extends TestCase
{
    /** @var MockObject|Message */
    private $messageMock;

    public function setUp()
    {
        $this->messageMock = $this->createMock(Message::class);
    }

    private function setUpMessageWithEmptyHeaders()
    {
        $this->messageMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([])
        ;
    }

    public function testToStringHasCorrectType()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertSame('mail-info', $result['type']);
    }

    public function testToStringHasCorrectId()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertSame(13, $result['data']['id']);
    }

    public function testToStringWithoutSubject()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertSame('', $result['data']['subject']);
    }

    public function testToStringWithSubject()
    {
        $messageMock = $this->createMock(Message::class);

        $messageMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['subject' => new HeaderBuffer('subject', 'SUBJECT')])
        ;

        $result = json_decode((string) new MailInfo(13, $messageMock), true);

        $this->assertSame('SUBJECT', $result['data']['subject']);
    }

    public function testToStringHasTimestamp()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertRegExp('~^[\d]{4}-[\d]{2}-[\d]{2}T[\d]{2}:[\d]{2}~', $result['data']['timestamp']);
    }

    public function testToStringHasFrom()
    {
        $this->messageMock
            ->expects($this->once())
            ->method('getFrom')
            ->willReturn('from@example.com')
        ;

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertSame('from@example.com', $result['data']['from']);
    }

    public function testToStringHasRecipients()
    {
        $this->messageMock
            ->expects($this->once())
            ->method('getRecipients')
            ->willReturn(['to@example.com'])
        ;

        $result = json_decode((string) new MailInfo(13, $this->messageMock), true);

        $this->assertSame(['to@example.com'], $result['data']['to']);
    }
}
