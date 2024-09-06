<?php

declare(strict_types=1);

namespace App\Message\Event;

class InquirySavedEvent
{
    public function __construct(private int|string $inquiryId) {}

    public function getInquiryId(): int|string {
        return $this->inquiryId;
    }
}