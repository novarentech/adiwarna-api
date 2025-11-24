<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTransmittal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transmittals';

    protected $fillable = [
        'name',
        'ta_no',
        'date',
        'customer_id',
        'customer_district',
        'pic_name',
        'report_type',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TransmittalDocument::class, 'transmittal_id');
    }
}
