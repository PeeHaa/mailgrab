<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Storage;

use PeeHaa\MailGrab\Http\Entity\Mail;

interface Storage
{
    public function add(Mail $mail): void;

    public function delete(string $id): void;

    public function get(string $id): Mail;

    public function has(string $id): bool;
}
