<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Response;

use PeeHaa\MailGrab\Http\Response\NewMail;
use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PeeHaa\MailGrab\Smtp\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NewMailTest extends TestCase
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

    public function testGetId()
    {
        $this->setUpMessageWithEmptyHeaders();

        $newMail = new NewMail($this->messageMock);

        $this->assertRegExp('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~', $newMail->getId());
    }

    public function testGetMessage()
    {
        $this->setUpMessageWithEmptyHeaders();

        $newMail = new NewMail($this->messageMock);

        $this->assertSame($this->messageMock, $newMail->getMessage());
    }

    public function testToStringHasCorrectType()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new NewMail($this->messageMock), true);

        $this->assertSame('new-mail', $result['type']);
    }

    public function testToStringHasCorrectId()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new NewMail($this->messageMock), true);

        $this->assertRegExp('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~', $result['data']['id']);
    }

    public function testToStringWithoutSubject()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new NewMail($this->messageMock), true);

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

        $result = json_decode((string) new NewMail($messageMock), true);

        $this->assertSame('SUBJECT', $result['data']['subject']);
    }

    public function testToStringHasTimestamp()
    {
        $this->setUpMessageWithEmptyHeaders();

        $result = json_decode((string) new NewMail($this->messageMock), true);

        $this->assertRegExp('~^[\d]{4}-[\d]{2}-[\d]{2}T[\d]{2}:[\d]{2}~', $result['data']['timestamp']);
    }
}
