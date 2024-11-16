<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;
    protected $fillable =  [
        'country',
        'code',
        'include',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function zipcodes(): HasMany
    {
        return $this->hasMany(Zipcode::class);
    }

    /** Solo registros con include = 1  */

    public function scopeInclude(Builder $query): void
    {
        $query->where('include', 1)->orderBy('country');
    }
}
