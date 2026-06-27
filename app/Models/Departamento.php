<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $primaryKey = 'id_departamento';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class, 'id_departamento', 'id_departamento');
    }
}
