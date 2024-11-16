<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;
    protected $fillable =  [
        'country_id',
        'state_id',
        'municipality_id',
        'name',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function zipcodes(): HasMany
    {
        return $this->hasMany(Zipcode::class);
    }

    public function colonies(): HasMany
    {
        return $this->hasMany(Zipcode::class,'zipcode');
    }
}
