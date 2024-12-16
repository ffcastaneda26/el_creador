<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;
    protected $table = 'warehouses';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'short',
        'email',
        'phone',
        'rfc',
        'active',
        'user_id',
    ];
    


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
