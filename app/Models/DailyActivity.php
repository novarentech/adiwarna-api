<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_no',
        'po_year',
        'ref_no',
        'customer_id',
        'date',
        'location',
        'time_from',
        'time_to',
        'prepared_name',
        'prepared_pos',
        'acknowledge_name',
        'acknowledge_pos',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(DailyActivityMember::class);
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(DailyActivityDescription::class);
    }
}
