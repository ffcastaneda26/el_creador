<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    protected $table = 'municipalities';
    public $timestamps = false;
    protected $fillable =  [
        'country_id',
        'state_id',
        'name',
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

    public function coverages(): HasMany
    {
        return $this->hasMany(DetailCoverage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function zipcodes(): HasMany
    {
        return $this->hasMany(Zipcode::class);
    }
}
