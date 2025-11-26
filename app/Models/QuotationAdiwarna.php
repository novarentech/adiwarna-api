<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationAdiwarna extends Model
{
    use HasFactory;
    protected $fillable = ['quotation_id', 'adiwarna_description'];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
}
