<?php

namespace App\Enums;

enum EmployeeCategory: string
{
    case STAFF = 'staff';
    case NON_STAFF = 'non-staff';

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
            self::STAFF => 'Staff',
            self::NON_STAFF => 'Non-Staff',
        };
    }
}
