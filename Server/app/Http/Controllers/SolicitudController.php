<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Recolector;
use App\Enums\TamanoResiduo;
use App\Enums\TipoResiduo;
use App\Services\SolicitudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    protected $solicitudService;

    public function __construct(SolicitudService $solicitudService)
    {
        $this->middleware('auth');
        $this->solicitudService = $solicitudService;
    }

    public function index()
    {
        $solicitudes = $this->solicitudService->getAllSolicitudes();
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
        $recolectores = Recolector::all();
        $tamanos = TamanoResiduo::cases();
        $tipos = TipoResiduo::cases();
        
        return view('solicitudes.create', compact('recolectores', 'tamanos', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'direccion_recojo' => 'required|string|max:255',
            'numero_referencia' => 'required|string|max:50',
            'detalles_casa' => 'required|string|max:255',
            'tipo_material' => 'required|string|max:255',
            'detalles_adicionales' => 'nullable|string',
            'fecha_programada' => 'required|date',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'tamano_residuo' => 'required|string',
            'tipo_residuo' => 'required|string',
        ]);

        // Si no se proporcionan coordenadas, obtenerlas de la dirección
        if (!$request->has('latitud') || !$request->has('longitud')) {
            $coordinates = $this->solicitudService->getCoordinates($request->direccion_recojo);
            
            if (!$coordinates) {
                return back()->withErrors(['direccion_recojo' => 'No se pudo obtener la ubicación de la dirección proporcionada.'])
                    ->withInput();
            }

            $request->merge([
                'latitud' => $coordinates['latitud'],
                'longitud' => $coordinates['longitud'],
                'direccion_recojo' => $coordinates['direccion_formateada']
            ]);
        }

        $solicitud = $this->solicitudService->createSolicitud($request->all());

        return redirect()->route('solicitudes.show', $solicitud)
            ->with('success', 'Solicitud creada exitosamente.');
    }

    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['usuario', 'recolector', 'historiales']);
        return view('solicitudes.show', compact('solicitud'));
    }

    public function edit(Solicitud $solicitud)
    {
        $recolectores = Recolector::all();
        $tamanos = TamanoResiduo::cases();
        $tipos = TipoResiduo::cases();
        
        return view('solicitudes.edit', compact('solicitud', 'recolectores', 'tamanos', 'tipos'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'direccion_recojo' => 'required|string|max:255',
            'numero_referencia' => 'required|string|max:50',
            'detalles_casa' => 'required|string|max:255',
            'tipo_material' => 'required|string|max:255',
            'detalles_adicionales' => 'nullable|string',
            'fecha_programada' => 'required|date',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'tamano_residuo' => 'required|string',
            'tipo_residuo' => 'required|string',
        ]);

        // Si la dirección ha cambiado o no se proporcionan coordenadas
        if ($request->direccion_recojo !== $solicitud->direccion_recojo || 
            (!$request->has('latitud') || !$request->has('longitud'))) {
            
            $coordinates = $this->solicitudService->getCoordinates($request->direccion_recojo);
            
            if (!$coordinates) {
                return back()->withErrors(['direccion_recojo' => 'No se pudo obtener la ubicación de la dirección proporcionada.'])
                    ->withInput();
            }

            $request->merge([
                'latitud' => $coordinates['latitud'],
                'longitud' => $coordinates['longitud'],
                'direccion_recojo' => $coordinates['direccion_formateada']
            ]);
        }

        $this->solicitudService->updateSolicitud($solicitud, $request->all());

        return redirect()->route('solicitudes.show', $solicitud)
            ->with('success', 'Solicitud actualizada exitosamente.');
    }

    public function destroy(Solicitud $solicitud)
    {
        $this->solicitudService->deleteSolicitud($solicitud);
        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud eliminada exitosamente.');
    }

    public function historial()
    {
        $solicitudes = $this->solicitudService->getHistorialUsuario();
        return view('solicitudes.historial', compact('solicitudes'));
    }

    public function historialAdmin()
    {
        $solicitudes = $this->solicitudService->getHistorialAdmin();
        return view('solicitudes.historial-admin', compact('solicitudes'));
    }

    public function historialRecolector()
    {
        $solicitudes = $this->solicitudService->getHistorialRecolector();
        return view('solicitudes.historial-recolector', compact('solicitudes'));
    }

    public function historialCliente()
    {
        $solicitudes = $this->solicitudService->getHistorialUsuario();
        return view('solicitudes.historial-cliente', compact('solicitudes'));
    }

    public function asignarRecolector(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'recolector_id' => 'required|exists:recolectores,id',
        ]);

        $this->solicitudService->asignarRecolector($solicitud, $request->recolector_id);

        return redirect()->back()
            ->with('success', 'Recolector asignado exitosamente.');
    }

    public function cambiarEstado(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,asignada,en_proceso,completada,cancelada',
        ]);

        $this->solicitudService->cambiarEstado($solicitud, $request->estado);

        return redirect()->back()
            ->with('success', 'Estado actualizado exitosamente.');
    }
} 