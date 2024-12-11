<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    protected $table = 'parts';
    public $timestamps = false;
    protected $fillable =  [
        'name',
    ];

    public function parts(): HasMany
    {
        return $this->hasMany(DetailPart::class);
    }

    public function manufacturigns(): HasMany
    {
        return $this->hasMany(ManufacturingPart::class);
    }
}
