<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivityMember extends Model
{
    protected $fillable = ['daily_activity_id', 'member_name'];

    public function dailyActivity(): BelongsTo
    {
        return $this->belongsTo(DailyActivity::class);
    }
}
