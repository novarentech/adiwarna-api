<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case TEKNISI = 'teknisi';

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
            self::ADMIN => 'Administrator',
            self::TEKNISI => 'Teknisi',
        };
    }
}
