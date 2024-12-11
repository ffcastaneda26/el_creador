<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anexo extends Model
{
    protected $table = "anexos";

    public $timestamps = false;
    protected $fillable =  [
        'anexo',
    ];

    public function logotipos():HasMany
    {
        return $this->hasMany(LogosManufacturing::class);
    }

}
