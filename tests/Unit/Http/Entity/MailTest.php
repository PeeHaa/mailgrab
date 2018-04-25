<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Entity;

use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Smtp\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{
    /** @var MockObject|Message */
    private $messageMock;

    public function setUp()
    {
        $this->messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message.txt'))
        ;

        $this->messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([
                'to.with.name@example.net'    => 'to.with.name@example.net',
                'to.without.name@example.com' => 'to.without.name@example.com',
                'cc@example.com'              => 'cc@example.com',
                'bcc@example.com'             => 'bcc@example.com',
            ])
        ;
    }

    public function testGetId()
    {
        $mail = new Mail($this->messageMock);

        $this->assertRegExp('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~', $mail->getId());
    }

    public function testGetFrom()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('"M. Sender" <from@example.com>', $mail->getFrom());
    }

    public function testGetTo()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('Joe User <to.with.name@example.net>, to.without.name@example.com', $mail->getTo());
    }

    public function testGetCc()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('cc@example.com', $mail->getCc());
    }

    public function testGetCcReturnsNullWhenNotAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-without-cc.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertNull($mail->getCc());
    }

    public function testGetBcc()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('bcc@example.com', $mail->getBcc());
    }

    public function testGetSubject()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('PHPMailer', $mail->getSubject());
    }

    public function testGetTimestamp()
    {
        $mail = new Mail($this->messageMock);

        $this->assertInstanceOf(\DateTimeImmutable::class, $mail->getTimestamp());
    }

    public function testGetText()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('This is the body in plain text for non-HTML mail clients', trim($mail->getText()));
    }

    public function testGetTextReturnsNUllWhenNotAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-without-text.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertNull($mail->getText());
    }

    public function testGetHtml()
    {
        $mail = new Mail($this->messageMock);

        $this->assertGreaterThan(10000, strlen($mail->getHtml()));
    }

    public function testGetHtmlReturnsNullWhenNotAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-without-html.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertNull($mail->getHtml());
    }

    public function testGetSource()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame(file_get_contents(DATA_DIR . '/raw-message.txt'), $mail->getSource());
    }

    public function testGetSearchableContentReturnsCorrectContentWhenHtmlNotAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-for-search-without-html.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('phpmailer this is the body in plain text for non-html mail clients', $mail->getSearchableContent());
    }

    public function testGetSearchableContentReturnsCorrectContentWhenTextNotAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-for-search-without-text.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('phpmailer this is the html message body in bold!', $mail->getSearchableContent());
    }

    public function testGetSearchableContentReturnsAllContentWhenAvailable()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-for-search.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('phpmailer this is the body in plain text for non-html mail clients this is the html message body in bold!', $mail->getSearchableContent());
    }

    public function testGetAttachmentsWithoutAttachments()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-without-html.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame([], $mail->getAttachments());
    }

    public function testGetAttachmentsReturnsBothAttachments()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertCount(2, $mail->getAttachments());
    }

    public function testGetAttachmentsReturnsCorrectId()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame(0, $mail->getAttachments()[0]['id']);
    }

    public function testGetAttachmentsReturnsCorrectName()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('pdf-sample.pdf', $mail->getAttachments()[0]['name']);
    }

    public function testGetAttachmentsReturnsCorrectContentType()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('application/pdf', $mail->getAttachments()[0]['content-type']);
    }

    public function testGetAttachmentReturnsCorrectId()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame(0, $mail->getAttachment(0)['id']);
    }

    public function testGetAttachmentReturnsCorrectName()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('pdf-sample.pdf', $mail->getAttachment(0)['name']);
    }

    public function testGetAttachmentReturnsCorrectContentType()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $this->assertSame('application/pdf', $mail->getAttachment(0)['content-type']);
    }

    public function testIsReadWhenNotRead()
    {
        $mail = new Mail($this->messageMock);

        $this->assertFalse($mail->isRead());
    }

    public function testSetRead()
    {
        $mail = new Mail($this->messageMock);

        $mail->setRead();

        $this->assertTrue($mail->isRead());
    }

    public function testGetProject()
    {
        $mail = new Mail($this->messageMock);

        $this->assertSame('0', $mail->getProject());
    }
}
