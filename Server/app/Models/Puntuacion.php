<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Puntuacion extends BaseModel
{
    use HasFactory;

    protected $table = 'puntuaciones';

    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'recolector_id',
        'puntuacion',
        'comentario',
        'estado',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function recolector()
    {
        return $this->belongsTo(Recolector::class, 'recolector_id');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
}

