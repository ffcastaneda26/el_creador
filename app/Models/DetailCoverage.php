<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailCoverage extends Model
{
    protected $table = 'detail_coverages';
    public $timestamps = false;
    protected $fillable =  [
        'coverage_id',
        'municipality_id',
    ];

    public function coverage(): BelongsTo
    {
        return $this->belongsTo(Coverage::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
