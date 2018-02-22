<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Response;

use PeeHaa\MailGrab\Smtp\HeaderBuffer;
use PeeHaa\MailGrab\Smtp\Message;
use Ramsey\Uuid\Uuid;

class NewMail
{
    private $id;

    private $message;

    private $subject = '';

    public function __construct(Message $message)
    {
        $this->id      = Uuid::uuid4()->toString();
        $this->message = $message;

        /** @var HeaderBuffer[] $headers */
        $headers = $message->getHeaders();

        if (isset($headers['subject'])) {
            $this->subject = $headers['subject']->getValue();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function __toString(): string
    {
        return json_encode([
            'type' => 'new-mail',
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
