<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'fecha',
        'codigo',
        'tipo',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones principales
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con HISTORIA (Asumiendo relación 1 a 1 o 1 a muchos, ajustamos a HasMany o HasOne según se requiera)
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    // Relaciones con HEMODIALISIS (medicals, nurses, treatments)
    public function medicals(): HasMany
    {
        return $this->hasMany(Medical::class);
    }

    public function nurses(): HasMany
    {
        return $this->hasMany(Nurse::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    // Relación con LABORATORIO
    public function laboratories(): HasMany
    {
        return $this->hasMany(Laboratory::class);
    }
}