<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'date',
        'approved',
        'date_approved',
        'advance',
        'pending_balance',
        'subtotal',
        'tax',
        'retencion_isr',
        'discount',
        'total',
        'delivery_date',
        'address',
        'street',
        'number',
        'interior_number',
        'colony',
        'references',
        'zipcode',
        'country_id',
        'state_id',
        'municipality_id',
        'city_id',
        'notes',
        'require_invoice',
        'payment_promise_date',
        'folio',
        'motley_name',
        'phone_whatsApp',
        'days_term',
        'shipping_company',
        'shipping_company_address',
        'shipping_cost',
        'cotization_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date'                 => 'datetime:Y-m-d',
            'date_approved'        => 'datetime:Y-m-d',
            'delivery_date'        => 'datetime:Y-m-d',
            'payment_promise_date' => 'datetime:Y-m-d',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function cotization(): BelongsTo
    {
        return $this->belongsTo(Cotization::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function manufacturing_order(): HasOne
    {
        return $this->hasOne(Manufacturing::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zipcode(): BelongsTo
    {
        return $this->belongsTo(Zipcode::class, 'zipcode');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
