<?php

namespace App\Models;

use App\Enums\TipoSolicitud;
use App\Enums\TamanoResiduo;
use App\Enums\TipoResiduo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'usuario_id',
        'recolector_id',
        'tipo',
        'descripcion',
        'direccion',
        'estado',
        'fecha_solicitud',
        'fecha_programada',
        'fecha_recojo',
        'tamano_residuo',
        'tipo_residuo'
    ];

    protected $casts = [
        'tipo' => TipoSolicitud::class,
        'tamano_residuo' => TamanoResiduo::class,
        'tipo_residuo' => TipoResiduo::class,
        'fecha_solicitud' => 'datetime',
        'fecha_programada' => 'datetime',
        'fecha_recojo' => 'datetime',
    ];

    // Relaciones

    // Usuario que creó la solicitud
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Recolector asignado
    public function recolector()
    {
        return $this->belongsTo(Recolector::class, 'recolector_id');
    }

    // Historiales relacionados a esta solicitud
    public function historiales()
    {
        return $this->hasMany(Historial::class, 'solicitud_id');
    }

    // Puntuaciones asociadas
    public function puntuaciones()
    {
        return $this->hasMany(Puntuacion::class, 'solicitud_id');
    }

    // Mutador para tamano_residuo (recibe el valor como string y lo convierte al enum)
    public function setTamanoResiduoAttribute($value)
    {
        $this->attributes['tamano_residuo'] = TamanoResiduo::from($value)->value;
    }

    // Accesor para tamano_residuo (devuelve el enum correspondiente)
    public function getTamanoResiduoAttribute($value)
    {
        return TamanoResiduo::from($value);
    }

    // Mutador para tipo_residuo (recibe el valor como string y lo convierte al enum)
    public function setTipoResiduoAttribute($value)
    {
        $this->attributes['tipo_residuo'] = TipoResiduo::from($value)->value;
    }

    // Accesor para tipo_residuo (devuelve el enum correspondiente)
    public function getTipoResiduoAttribute($value)
    {
        return TipoResiduo::from($value);
    }

    // Accesor para obtener la ubicación como dirección formateada (si es necesario)
    public function getUbicacionAttribute()
    {
        return $this->latitud . ', ' . $this->longitud;
    }
}