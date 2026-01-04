<?php

namespace App\Models;

use App\Enums\DeliveryNoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dn_no',
        'date',
        'customer_id',
        'wo_no',
        'delivered_with',
        'vehicle_plate',
        'delivered_by',
        'received_by',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => DeliveryNoteStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }

    /**
     * Get formatted dn_date with Roman numerals from date field
     */
    public function getDnDateAttribute(): string
    {
        if (!$this->date) {
            return '';
        }

        $month = (int) $this->date->format('n'); // 1-12
        $year = $this->date->format('Y');

        $romanNumerals = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $romanMonth = $romanNumerals[$month] ?? $month;

        return $romanMonth . '/' . $year;
    }
}
