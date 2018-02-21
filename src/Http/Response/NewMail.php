<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Response;

use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PeeHaa\MailGrab\Smtp\Message;

class NewMail
{
    private $subject = '';

    private $message;

    public function __construct(Message $message)
    {
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
            'type' => 'new-mail',
            'data' => [
                'subject'   => $this->subject,
                'timestamp' => (new \DateTime())->format(\DateTime::RFC3339_EXTENDED),
                'from'      => $this->message->getFrom(),
            ],
        ]);
    }
}
