<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Response;

use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PeeHaa\MailGrab\Smtp\Message;

class MailInfo
{
    private $id;

    private $message;

    private $subject = '';

    public function __construct(int $id, Message $message)
    {
        $this->id      = $id;
        $this->message = $message;

        /** @var HeaderBuffer[] $headers */
        $headers = $message->getHeaders();

        if (isset($headers['subject'])) {
            $this->subject = $headers['subject']->getValue();
        }
    }

    public function __toString(): string
    {
        return json_encode([
            'type' => 'mail-info',
            'data' => [
                'id'        => $this->id,
                'subject'   => $this->subject,
                'timestamp' => (new \DateTime())->format(\DateTime::RFC3339_EXTENDED),
                'from'      => $this->message->getFrom(),
                'to'        => $this->message->getRecipients(),
            ],
        ]);
    }
}
