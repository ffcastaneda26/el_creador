<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    protected $table = 'receipt_details';
    protected $fillable =  [
        'receipt_id',
        'product_id',
        'quantity',
        'cost',
    ];

    protected function casts(): array
    {
        return [

        ];
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
