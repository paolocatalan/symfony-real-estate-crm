<?php

declare(strict_types=1);

namespace App\Config;

enum PropertyStatusEnum: string
{
    case Available = 'Available';
    case Pending = 'Pending';
    case Sold = 'Sold';
}
