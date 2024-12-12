<?php

namespace App\Models;

use App\Observers\ManufacturingPartObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingPart extends Model
{
    protected $table = "manufacturing_parts";

    protected $fillable = [
        'manufacturing_id',
        'part_id',
        'child_part_id',
        'color',
        'material',
        'value',
        'include',
        'user_id'
    ];

    protected static function booted()
    {
        static::observe(ManufacturingPartObserver::class);
    }

    public function manufacturing(): BelongsTo
    {
        return $this->belongsTo(Manufacturing::class);
    }

    public function part():BelongsTo
    {
        return $this->belongsTo(Part::class)->where('parent_part',1)->orderBy('id');
    }

    public function child_part():BelongsTo
    {
        return $this->belongsTo(Part::class)->where('parent_part',0)->orderBy('name');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
