<?php

namespace App\Models;

use App\Enums\Enums\KeyMovementTypeEnum;
use App\Enums\Enums\KeyMovementUsedToEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'type'      => KeyMovementTypeEnum::class,
            'used_to'   =>KeyMovementUsedToEnum::class,
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
