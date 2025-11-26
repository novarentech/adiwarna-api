<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LEAVE = 'leave';
    case SICK = 'sick';
    case HOLIDAY = 'holiday';

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
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::LEAVE => 'Leave',
            self::SICK => 'Sick Leave',
            self::HOLIDAY => 'Holiday',
        };
    }

    /**
     * Check if status counts as working day
     */
    public function isWorkingDay(): bool
    {
        return $this === self::PRESENT;
    }
}
