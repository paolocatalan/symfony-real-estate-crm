<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\InquirySavedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler()]
class InquirySavedEventHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(InquirySavedEvent $event)
    {
        // throw new \RuntimeException('ORDER COULD NOT BE FOUND.');
        // https://github.com/mpdf/mpdf
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