<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    public $timestamps = false;

    protected $guarded = [];
    protected $fillable = [
        'title',
        'description',
        'color',
        'starts_at',
        'ends_at',
    ];

}
