<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fecha_ingreso_hd' => 'date',
        'antecedentes_personales' => 'array',
        'biopsia_renal' => 'boolean',
        'd_peritoneal' => 'boolean',
        't_renal' => 'boolean',
        'o_fecha' => 'date',
        'hiv' => 'boolean',
        'hbsag' => 'boolean',
        'anti_hbc' => 'boolean',
        'vhc' => 'boolean',
        'anti_hbs' => 'boolean',
        'rpr' => 'boolean',
        'diagnostico' => 'array',
        'f_alta' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
