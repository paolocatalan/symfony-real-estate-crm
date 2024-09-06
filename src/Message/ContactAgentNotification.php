<?php

declare(strict_types=1);

namespace App\Message;

class ContactAgentNotification
{
    public function __construct(private int $messageId) {}

    public function getMessageId(): int {
        return $this->messageId;
    }
}
