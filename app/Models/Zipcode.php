<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zipcode extends Model
{
    protected $table = 'zipcodes';
    public $timestamps = false;
    protected $fillable =  [
        'country_id',
        'state_id',
        'municipality_id',
        'city_id',
        'zipcode',
        'country',
        'state',
        'municipality',
        'city',
        'name',
        'type_zipcode_id',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class,'zipcode');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function orders():HasMany
    {
        return $this->hasMany(Order::class,'zipcode');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeZipcode::class,'type_zipcode_id');
    }
}
