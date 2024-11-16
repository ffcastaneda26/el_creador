<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable =  [
        'name',
        'email',
        'phone',
        'mobile',
        'curp',
        'ine',
        'rfc',
        'address',
        'num_int',
        'colony',
        'zipcode',
        'type',
        'country_id',
        'state_id',
        'municipality_id',
        'city_id',
        'notes',
        'references'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => ucfirst($value),
        );
    }

    protected function rfc(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    protected function curp(): Attribute
    {

        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    public function cotizations(): HasMany
    {
        return $this->hasMany(Cotization::class);
    }


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function zipcode():BelongsTo
    {
        return $this->belongsTo(Zipcode::class,'zipcode');
    }

    /**
     * Busca clientes
     */
    public function scopeSearch(Builder $query,$search): void
    {
        $search = trim($search);
        $query->where('name', 'like', "%{$this->search}%")
                ->orwhere('email', 'like', "%{$this->search}%")
                ->orwhere('phone', 'like', "%{$this->search}%")
                ->orwhere('mobile', 'like', "%{$this->search}%");

    }
}
