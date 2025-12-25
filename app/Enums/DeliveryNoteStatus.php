<?php

namespace App\Enums;

enum DeliveryNoteStatus: string
{
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
