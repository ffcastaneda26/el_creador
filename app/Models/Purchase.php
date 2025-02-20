<?php

namespace App\Models;

use App\Enums\Enums\StatusPurchaseEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function authorizer_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_auhtorizer_id');
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Actualiza Estado
     */

}
