<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
    ];

}
