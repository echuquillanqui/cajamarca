<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fecha_nacimiento' => 'date:Y-m-d',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia')
            ->where('id_departamento', $this->id_departamento);
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'id_distrito', 'id_distrito')
            ->where('id_departamento', $this->id_departamento)
            ->where('id_provincia', $this->id_provincia);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

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

    public function laboratories(): HasMany
    {
        return $this->hasMany(Laboratory::class);
    }
}
