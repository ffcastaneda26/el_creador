<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coverage extends Model
{
    protected $table = 'coverages';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'distance',
        'notes',
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(DetailCoverage::class);
    }

}
