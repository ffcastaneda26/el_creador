<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $table = 'metrics';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'description',
        'measure'
    ];
}
