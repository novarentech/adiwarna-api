<?php

namespace App\Enums;

enum EquipmentCondition: string
{
    case OK = 'ok';
    case REPAIR = 'repair';
    case REJECT = 'reject';

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
            self::OK => 'OK (Fit for Use)',
            self::REPAIR => 'Repair',
            self::REJECT => 'Reject',
        };
    }
}
