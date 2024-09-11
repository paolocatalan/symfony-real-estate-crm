<?php declare(strict_types=1);

namespace App\Message;

class ContactAgentNotification
{
    public function __construct(private int|string $notificationId) {}

    public function getNotificationId(): int|string {
        return $this->notificationId;
    }
}
