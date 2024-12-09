<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPart extends Model
{
    protected $table = 'detail_parts';
    public $timestamps = false;
    protected $fillable =  [
        'part_id',
        'child_part_id',
        'color',
        'material',
        'value',
        'require',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function child_part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

}
