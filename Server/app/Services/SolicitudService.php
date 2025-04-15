<?php

namespace App\Services;

use App\Models\Solicitud;
use App\Models\User;
use App\Models\Recolector;
use App\Enums\TamanoResiduo;
use App\Enums\TipoResiduo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudService
{
    protected $geocodingService;

    public function __construct(GeocodingService $geocodingService = null)
    {
        $this->geocodingService = $geocodingService;
    }

    public function getAllSolicitudes()
    {
        return Solicitud::with(['usuario', 'recolector'])
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);
    }

    public function createSolicitud(array $data)
    {
        return Solicitud::create([
            'usuario_id' => Auth::id(),
            'direccion_recojo' => $data['direccion_recojo'],
            'numero_referencia' => $data['numero_referencia'],
            'detalles_casa' => $data['detalles_casa'],
            'tipo_material' => $data['tipo_material'],
            'detalles_adicionales' => $data['detalles_adicionales'] ?? null,
            'estado' => 'pendiente',
            'fecha_solicitud' => now(),
            'fecha_programada' => $data['fecha_programada'],
            'latitud' => $data['latitud'],
            'longitud' => $data['longitud'],
            'tamano_residuo' => $data['tamano_residuo'],
            'tipo_residuo' => $data['tipo_residuo'],
        ]);
    }

    public function updateSolicitud(Solicitud $solicitud, array $data)
    {
        $solicitud->update($data);
        return $solicitud;
    }

    public function deleteSolicitud(Solicitud $solicitud)
    {
        $solicitud->delete();
    }

    public function getHistorialUsuario()
    {
        return Solicitud::with(['usuario', 'recolector'])
            ->where('usuario_id', Auth::id())
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);
    }

    public function getHistorialAdmin()
    {
        return Solicitud::with(['usuario', 'recolector'])
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);
    }

    public function getHistorialRecolector()
    {
        return Solicitud::with(['usuario', 'recolector'])
            ->where('recolector_id', Auth::id())
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);
    }

    public function asignarRecolector(Solicitud $solicitud, int $recolectorId)
    {
        $solicitud->update([
            'recolector_id' => $recolectorId,
            'estado' => 'asignada'
        ]);
        return $solicitud;
    }

    public function cambiarEstado(Solicitud $solicitud, string $estado)
    {
        $solicitud->update([
            'estado' => $estado
        ]);
        return $solicitud;
    }

    public function getCoordinates($direccion)
    {
        if ($this->geocodingService) {
            return $this->geocodingService->getCoordinates($direccion);
        }
        return null;
    }
} 