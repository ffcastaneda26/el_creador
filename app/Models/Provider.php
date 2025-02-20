<?php

namespace App\Models;

use Attribute;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'providers';

    protected $fillable =  [
        'name',
        'email',
        'phone',
        'mobile',
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
        'references',
        'active'
    ];

    // protected function name(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => ucfirst($value),
    //         set: fn (string $value) => ucfirst($value),
    //     );
    // }




    public function setRfcAttribute($value)
    {
        $this->attributes['rfc'] = strtoupper($value);
    }

    public function setCurpAttribute($value)
    {
        $this->attributes['curp'] = strtoupper($value);
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

    public isActive(){
        return $this->active;
    }
}
