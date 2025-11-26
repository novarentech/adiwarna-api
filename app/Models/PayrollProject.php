<?php

namespace App\Models;

use App\Enums\PayrollProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PayrollProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => PayrollProjectStatus::class,
        ];
    }

    public function periods(): HasMany
    {
        return $this->hasMany(PayrollPeriod::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', PayrollProjectStatus::ACTIVE);
    }

    public function canCreatePeriods(): bool
    {
        return $this->status === PayrollProjectStatus::ACTIVE;
    }
}
