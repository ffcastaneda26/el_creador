<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable =  [
        'name',
        'last_name',
        'mother_surname',
        'company_name',
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
        'tax_type',
        'iva',
        'retencion',
        'street',
        'number',
        'interior_number',
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
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->name} {$this->last_name} {$this->mother_surname}",
        );
    }

//     public function setaddressAttribute($value):
//     {
//         $this->attributes['address']  = $this->street . ' ' . $this->number . ' ' . $this->interior_number;
//    }


    public function setaddressAttribute($value)
    {
        if($this->interior_number){
            $this->attributes['address'] = ucfirst($this->street) . ' ' .  $this->number . ' -' . $this->interior_number;

        }else{
        $this->attributes['address'] = ucfirst($this->street) . ' ' .  $this->number;

        }
    }

    public function setRfcAttribute($value)
    {
        $this->attributes['rfc'] = strtoupper($value);
    }

    public function setCurpAttribute($value)
    {
        $this->attributes['curp'] = strtoupper($value);
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

    public function pending_orders(): HasMany
    {
        return $this->hasMany(Order::class)->where('pending_balance', '>', 0);
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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
