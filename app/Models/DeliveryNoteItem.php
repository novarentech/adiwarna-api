<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id',
        'item_name',
        'serial_number',
        'qty',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
        ];
    }

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
    }
}
