<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Entity;

class Recipient
{
    private $name;

    private $email;

    public function __construct(string $email, string $name)
    {
        $this->email = $email;
        $this->name  = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
