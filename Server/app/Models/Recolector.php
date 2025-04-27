<?php

namespace App\Models;

class Recolector extends BaseModel
{
    protected $table = 'recolectores';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'email',
        'direccion',
        'licencia',
        'estado',
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at',
    ];
}
