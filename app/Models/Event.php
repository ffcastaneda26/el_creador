<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $table = 'events';
    public $timestamps = false;

    protected $guarded = [];
    protected $fillable = [
        'order_id',
        'title',
        'description',
        'color',
        'starts_at',
        'ends_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
