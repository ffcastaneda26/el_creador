<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingPart extends Model
{
    protected $table = "manufacturing_parts";

    // $table->foreignIdFor(Manufacturing::class)->comment('Orden de fabricación');
    // $table->foreignIdFor(Part::class)->comment('Parte');
    // $table->string('color',30)->nullable()->default('Color');
    // $table->string('material',30)->nullable()->default('Material');
    // $table->string('value',30)->nullable()->default(null)->comment('Valor');
    // $table->boolean('include')->default(0)->comment('¿Incluir o no?');
    // $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');

    protected $fillable = [
        'manufacturing_id',
        'part_id',
        'color',
        'material',
        'value',
        'require',
        'user_id'
    ];

    public function manufacturing(): BelongsTo
    {
        return $this->belongsTo(Manufacturing::class);
    }

    public function part():BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
