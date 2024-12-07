<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    protected $table = 'partes';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'color',
        'material'
    ];
}
