<?php

namespace App\Enums;

enum MaterialReceivingReportRemarks: string
{
    case GOOD = 'good';
    case REJECT = 'reject';

    public function label(): string
    {
        return match ($this) {
            self::GOOD => 'Good',
            self::REJECT => 'Reject',
        };
    }
}
