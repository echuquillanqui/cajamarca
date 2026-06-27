<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    protected $guarded = [];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function distritos(): HasMany
    {
        return $this->hasMany(Distrito::class, 'id_provincia', 'id_provincia')
            ->where('id_departamento', $this->id_departamento);
    }
}
