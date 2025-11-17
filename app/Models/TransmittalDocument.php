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
        'work_reference',
        'document_no',
        'document_year',
    ];

    public function transmittal(): BelongsTo
    {
        return $this->belongsTo(DocumentTransmittal::class, 'transmittal_id');
    }
}
