<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $table = 'images';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'description',
        'image',
        'imageable_type',
        'imageable_id'
    ];

    protected static function booted()
    {
        static::deleted(function(Image $image){
            Storage::delete("public/".$image->image);
        });
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}
