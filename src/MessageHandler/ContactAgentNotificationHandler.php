<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ContactAgentNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ContactAgentNotificationHandler
{
    public function __invoke(ContactAgentNotification $notification)
    {
        echo 'Creating a PDF contract note...<br>';

        echo 'Emailing contract note to '. $notification->getMessage()->getUser()->getEmail()  .'...<br>';
    }
}