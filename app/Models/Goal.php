<?php

namespace App\Models;

use App\Enums\Enums\GoalPeriodEnum;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals';

    protected $fillable = [
        'metric_id',
        'user_id',
        'period',
        'start_date',
        'end_date',
        'goal_units',
        'goal_amount',
    ];

    protected $casts = [
        'start_date'    => 'datetime:Y-m-d',
        'end_date'      => 'datetime:Y-m-d',
        'goal_units'    => 'integer',
        'goal_amount'   => 'decimal:2',
        'period'        => GoalPeriodEnum::class,
    ];

    public function metric()
    {
        return $this->belongsTo(Metric::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
