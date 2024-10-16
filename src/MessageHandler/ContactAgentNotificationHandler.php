<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ContactAgentNotification;
use Mpdf\Mpdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class ContactAgentNotificationHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(ContactAgentNotification $notification): void
    {
        $mpdf = new Mpdf();

        $content = "<h1>Letter of Guarantee</h1>";
        $content .= "For Buyer ID: Using Working Now " . $notification->getName();

        $mpdf->WriteHTML($content);
        $propertyDocuments = $mpdf->Output('', 'S');

        // echo 'Email is on its way...' . PHP_EOL;
        $email = (new Email())
                    ->from('onboarding@resend.dev')
                    ->to('paolo_catalan@yahoo.com')
                    ->subject('Contract note for message ID : ' . $notification->getName())
                    ->text('Here is your contract note. Using Working Now')
                    ->attach($propertyDocuments, 'property-documents.pdf');

        $this->mailer->send($email);
    }
}
