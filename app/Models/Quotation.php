<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'ref_no',
        'ref_year',
        'customer_id',
        'pic_name',
        'pic_phone',
        'subject',
        'top',
        'valid_until',
        'clause',
        'workday',
        'auth_name',
        'auth_position',
        'discount',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date'
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function adiwarnas(): HasMany
    {
        return $this->hasMany(QuotationAdiwarna::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(QuotationClient::class);
    }
}
