<?php

namespace App\Models;

use App\Enums\Enums\StatusPurchaseEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Enums\StatusPurchaseDetailEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;
    protected $table = 'purchases';

    protected $fillable =  [
        'provider_id',
        'folio',
        'date',
        'amount',
        'notes',
        'user_id',
        'user_authorizer_id',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'date'      => 'datetime',
            'status'    => StatusPurchaseEnum::class,
        ];
    }


    public function authorizer_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_authorizer_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function pendings_to_receive(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class)->where('status', '!=',StatusPurchaseDetailEnum::surtida);
    }
    public function has_pendings_to_receive(): bool
    {
        return $this->pendings_to_receive()->count() > 0;
    }

    public function partial_received()
    {
        return $this->hasMany(PurchaseDetail::class)->where('status',StatusPurchaseDetailEnum::parcial);
    }

    public function has_partial_received()
    {
        return $this->partial_received()->count() > 0;
    }

    public function details_received(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class)->where('status','!=', StatusPurchaseDetailEnum::pendiente);
    }
    // Necesito saber si tiene al menos una partida surtida parcial o totalmente
     public function has_details_received(): bool
     {
        return $this->details_received->count() > 0;
     }


    public function partial_receive_items(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class)->where('status', StatusPurchaseDetailEnum::parcial);
    }
    public function has_partial_receive(): bool
    {
        return $this->partial_receive_items()->count() > 0;
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recepts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * Actualiza Estado
     */
    public function updateStatus()
    {

       if(!$this->has_pendings_to_suply()){
           $this->status = StatusPurchaseEnum::surtido;
       }
       if(!$this->has_partial_suply()){
           $this->status = StatusPurchaseEnum::parcial;
       }


       $this->save();

    }

    public function scopeStatus(Builder $query,$status){
        $query->where('status',$status);
    }
}
