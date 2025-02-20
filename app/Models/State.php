<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table = 'states';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'abbreviated',
        'contry_id'
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function municipalities(): HasMany
    {
        return $this->hasMany(Municipality::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }
    public function zipcodes(): HasMany
    {
        return $this->hasMany(Zipcode::class);
    }

}
