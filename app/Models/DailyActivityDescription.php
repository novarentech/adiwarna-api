<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivityDescription extends Model
{
    protected $fillable = ['daily_activity_id', 'description', 'equipment_no'];

    public function dailyActivity(): BelongsTo
    {
        return $this->belongsTo(DailyActivity::class);
    }
}
