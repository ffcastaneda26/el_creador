<?php
namespace App\Models;

use App\Enums\Enums\PaymentPromiseStatusEnum;
use Illuminate\Database\Eloquent\Builder;
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
        'delivered',
        'delivered_at',
        'delivered_by',
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
            'delivered'            => 'boolean',
            'delivered_at'         => 'datetime:Y-m-d H:i:s',
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

    public function event(): HasOne
    {
        return $this->hasOne(Event::class);
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

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function zipcode(): BelongsTo
    {
        return $this->belongsTo(Zipcode::class, 'zipcode');
    }

    /**
     * Lo que el cliente realmente ha pagado (suma de sus pagos registrados).
     */
    public function getPaidAmountAttribute(): float
    {
        return round((float) $this->payments()->sum('amount'), 2);
    }

    /**
     * Estado de cobranza: separa lo cobrado de lo unicamente prometido.
     *
     * El saldo se considera prometido cuando hay una `payment_promise_date`
     * capturada; si esa fecha ya paso y sigue habiendo saldo, esta vencido.
     */
    public function getPaymentPromiseStatusAttribute(): PaymentPromiseStatusEnum
    {
        if (round((float) $this->pending_balance, 2) <= 0) {
            return PaymentPromiseStatusEnum::liquidado;
        }

        if ($this->payment_promise_date === null) {
            return PaymentPromiseStatusEnum::sin_compromiso;
        }

        return $this->payment_promise_date->endOfDay()->isPast()
            ? PaymentPromiseStatusEnum::vencido
            : PaymentPromiseStatusEnum::comprometido;
    }

    /**
     * Dias de atraso sobre la fecha comprometida. Cero si no hay atraso.
     */
    public function getDaysOverdueAttribute(): int
    {
        if ($this->payment_promise_status !== PaymentPromiseStatusEnum::vencido) {
            return 0;
        }

        // Se comparan dias calendario para que "vencio hace 9 dias" reporte 9.
        return (int) $this->payment_promise_date->startOfDay()->diffInDays(now()->startOfDay());
    }

    /**
     * Pedidos con saldo cuya fecha comprometida ya vencio.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('pending_balance', '>', 0)
            ->whereNotNull('payment_promise_date')
            ->whereDate('payment_promise_date', '<', now()->toDateString());
    }

    /**
     * Recalcula el anticipo y el saldo a partir de los pagos realmente registrados.
     *
     * Los pagos son la unica fuente de verdad del dinero cobrado: `advance` es la
     * suma de los pagos y `pending_balance` lo que falta contra el total. Se guarda
     * en silencio para no volver a disparar los observers del pedido.
     */
    public function recalculateBalance(): void
    {
        $paid = (float) $this->payments()->sum('amount');

        $this->advance = round($paid, 2);
        $this->pending_balance = round(((float) $this->total) - $paid, 2);

        $this->saveQuietly();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
