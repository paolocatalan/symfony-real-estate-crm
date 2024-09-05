<?php

declare(strict_types=1);

namespace App\Message;

class ContactAgentNotification
{
    public function __construct(private object $message) {}

    public function getMessage(): object {
        return $this->message;
    }
}
