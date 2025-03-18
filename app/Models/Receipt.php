<?php

namespace App\Models;

use App\Enums\Enums\StatusReceiptEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;
    protected $table = 'receipts';

    protected $fillable =  [
        'purchase_id',
        'folio',
        'date',
        'amount',
        'tax',
        'total',
        'reference',
        'notes',
        'user_id',
        'user_authorizer_id',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'date'      => 'datetime',
            'status'    => StatusReceiptEnum::class,
        ];
    }


    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function authorizer_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_authorizer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReceiptDetail::class);
    }

    public function has_details(): bool
    {
        return $this->details()->count() > 0;
    }

    /**
     * Actualiza Estado
     */
    public function updateStatus($status)
    {
        $this->status = $status;

        $this->save();
    }

    public function checkAmount(){

        return $this->amount ==  $this->getDetailAmount();

    }

    /**
     * Obtiene el total del detalle de las partidas
     */

     public function getDetailAmount(){
        $sum_deatil_amount = 0;
        foreach($this->details as $detail){
            $sum_deatil_amount +=  round($detail->quantity * $detail->cost,2);
        }
        return  round($sum_deatil_amount,2);
     }



}


