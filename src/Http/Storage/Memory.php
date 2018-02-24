<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Storage;

use PeeHaa\MailGrab\Http\Entity\Mail;

class Memory implements Storage, \Iterator
{
    private $mails = [];

    public function add(Mail $mail): void
    {
        $this->mails[$mail->getId()] = $mail;
    }

    public function delete(string $id): void
    {
        unset($this->mails[$id]);
    }

    public function get(string $id): Mail
    {
        return $this->mails[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->mails[$id]);
    }

    public function current(): Mail
    {
        return current($this->mails);
    }

    public function next(): void
    {
        next($this->mails);
    }

    public function key(): string
    {
        return key($this->mails);
    }

    public function valid(): bool
    {
        return key($this->mails) !== null;
    }

    public function rewind()
    {
        reset($this->mails);
    }
}
