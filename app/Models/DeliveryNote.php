<?php

namespace App\Models;

use App\Enums\DeliveryNoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'delivery_note_no',
        'date',
        'customer',
        'customer_address',
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

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }
}
