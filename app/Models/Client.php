<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable =  [
        'name',
        'email',
        'phone',
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

    protected function rfc(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }


}
