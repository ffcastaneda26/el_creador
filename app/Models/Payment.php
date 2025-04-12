<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'client_id',
        'order_id',
        'payment_method_id',
        'date',
        'amount',
        'reference',
        'reference_number',
        'voucher_image',
        'notes',
    ];

    protected $casts = [
        'date'      => 'datetime:Y-m-d',
        'amount'    => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
