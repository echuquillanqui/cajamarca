<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distrito extends Model
{
    protected $guarded = [];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia')
            ->where('id_departamento', $this->id_departamento);
    }
}
