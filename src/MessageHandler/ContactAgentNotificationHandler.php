<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ContactAgentNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class ContactAgentNotificationHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(ContactAgentNotification $notification)
    {
        echo 'Creating a PDF contract note' . PHP_EOL;

        // echo 'Emailing contract note to '. $notification->getMessage()->getUser()->getEmail() . PHP_EOL;
        $email = (new Email())
                    ->from('onboarding@resend.dev')
                    ->to('paolo_catalan@yahoo.com')
                    ->subject('Contract note for message ID : 89724')
                    ->text('Here is your contract note');

        $this->mailer->send($email);
    }
}