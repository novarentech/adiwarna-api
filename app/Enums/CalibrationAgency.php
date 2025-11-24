<?php

namespace App\Enums;

enum CalibrationAgency: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

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
            self::INTERNAL => 'Internal',
            self::EXTERNAL => 'External',
        };
    }
}
