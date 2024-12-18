<?php

namespace App\Models;

use App\Enums\Enums\KeyMovementTypeEnum;
use App\Enums\Enums\KeyMovementUsedToEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeyMovement extends Model
{
    protected $table='key_movements';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'shoet',
        'used_to',
        'type',
        'require_cost',
        'is_purchase',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'type'      => KeyMovementTypeEnum::class,
            'used_to'   =>KeyMovementUsedToEnum::class,
        ];
    }

    public function isTypeI(): bool
    {
        return $this->type === KeyMovementTypeEnum::I;
    }


    public function movements(): HasMany
    {
        return $this->HasMany(Movement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
