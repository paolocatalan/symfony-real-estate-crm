<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Message\Command\SaveInquiry;
use App\Message\Event\InquirySavedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SaveInquiryHandler
{
    public function __construct(private MessageBusInterface $eventBus)
    {
        
    }
    public function __invoke(SaveInquiry $saveInquiry)
    {
        $inquiryId = 123;

        echo 'Inquiry being saved' . PHP_EOL;

        $this->eventBus->dispatch(new InquirySavedEvent($inquiryId));
    }
}