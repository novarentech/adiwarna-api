<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransmittalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'transmittal_id',
        'wo_number',
        'wo_year',
        'location',
    ];

    public function transmittal(): BelongsTo
    {
        return $this->belongsTo(DocumentTransmittal::class, 'transmittal_id');
    }
}
