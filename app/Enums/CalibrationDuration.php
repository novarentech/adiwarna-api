<?php

namespace App\Enums;

enum CalibrationDuration: int
{
    case SIX_MONTHS = 6;
    case TWELVE_MONTHS = 12;

    /**
     * Get all values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get label for display
     */
    public function label(): string
    {
        return match ($this) {
            self::SIX_MONTHS => '6 Months',
            self::TWELVE_MONTHS => '12 Months',
        };
    }
}
