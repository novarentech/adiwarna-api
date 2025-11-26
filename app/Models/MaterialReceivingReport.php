<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialReceivingReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rr_no',
        'rr_year',
        'date',
        'ref_pr_no',
        'ref_po_no',
        'supplier',
        'receiving_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'receiving_date' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialReceivingReportItem::class);
    }
}
