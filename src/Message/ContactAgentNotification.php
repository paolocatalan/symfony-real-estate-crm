<?php declare(strict_types=1);

namespace App\Message;

class ContactAgentNotification
{
    public function __construct(private string $name) {}

    public function getName(): string {
        return $this->name;
    }
}
