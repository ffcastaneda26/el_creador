<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;

    protected $table = 'units';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'symbol',
        'type',
        'description'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function has_products(): bool
    {
        return $this->products()->exists();
    }

    public function can_delete() : bool
    {
        return !$this->has_products();
    }
}
