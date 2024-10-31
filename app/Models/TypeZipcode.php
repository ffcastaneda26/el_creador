<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeZipcode extends Model
{
    protected $table = 'type_zipcodes';
    public $timestamps = false;
    protected $fillable =  [
        'type'
    ];



    public function zipcodes(): HasMany
    {
        return $this->hasMany(Zipcode::class,);
    }
}
