<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Enums\TamanoResiduo;
use App\Enums\TipoResiduo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Historial;
use App\Enums\TipoEventoHistorial;
use Illuminate\Support\Facades\Auth;
use App\Enums\TipoEventoSolicitud;


class SolicitudController extends Controller
{


    public function __construct()
{
// Métodos que NO requieren autenticación
    $this->middleware('guest')->only(['show', 'listarPorUsuario']);

    // Métodos que requieren estar logueado
    $this->middleware('auth:sanctum')->only(['update']);

    // Métodos que requieren ser admin o moderador ((solo para pruebas))
    // $this->middleware(['auth:sanctum', 'isAdminOrModerator'])->except(['store', 'show', 'listarPorUsuario','update']);
}




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $solicitudes = Solicitud::with(['usuario', 'recolector'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $solicitudes,
            'message' => 'Solicitudes obtenidas exitosamente'
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'nullable|exists:users,id',
            'nombre' => 'required|string|max:255', 
            'carnet' => 'required|string|max:20',
            'recolector_id' => 'nullable|exists:recolectores,id', // si no siempre viene, ponelo nullable
            'direccion_recojo' => 'required|string|max:255',
            'numero_referencia' => 'required|string|max:50',
            'detalles_casa' => 'nullable|string',
            /* 'tipo_material' => 'required|string|max:255', */
            'detalles_adicionales' => 'nullable|string',
            'estado_solicitud' => [
                'nullable', // o 'required' si querés que lo manden siempre
                Rule::in(collect(TipoEventoSolicitud::cases())->pluck('value'))
            ],
            'fecha_solicitud' => 'required|date',
            'fecha_programada' => 'nullable|date',
            'fecha_recojo' => 'nullable|date',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'tamano_residuo' => [
                'required',
                Rule::in(collect(TamanoResiduo::cases())->pluck('value'))
            ],
            'tipo_residuo' => [
                'required',
                Rule::in(collect(TipoResiduo::cases())->pluck('value'))
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Error de validación'
            ], 422);
        }

        /* $solicitud = Solicitud::create($validator->validated()); */

        $data = $validator->validated();

           $user = Auth::guard('sanctum')->user();
    if ($user) {
        $data['usuario_id'] = $user->id;
    }

        // Si no vino el estado, lo seteamos a pendiente por defecto
        $data['estado_solicitud'] ??= TipoEventoSolicitud::Creada->value;

        $solicitud = Solicitud::create($data);

        // Crear historial correspondiente
        Historial::create([
            /* 'usuario_id'    => $data['usuario_id'], */
            /*     'usuario_id'   => auth()->id(), // quien está logueado y creó la solicitud (operador o el mismo user) */

            'usuario_id'   => $user?->id,
            'solicitud_id'  => $solicitud->id,
            'tipo_evento'   => TipoEventoSolicitud::Creada, // Enum
            'detalle'       => 'La solicitud fue creada.',
            'fecha'         => now(),
        ]);


        return response()->json([
            'data' => $solicitud->load(['usuario', 'recolector']),
            'message' => 'Solicitud creada exitosamente'
        ], 201);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $solicitud = Solicitud::with(['usuario', 'recolector', 'historiales', 'puntuaciones'])
            ->find($id);

        if (!$solicitud) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        return response()->json([
            'data' => $solicitud,
            'message' => 'Solicitud obtenida exitosamente'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            /* 'usuario_id' => 'sometimes|exists:users,id', */
            'direccion_recojo' => 'sometimes|string|max:255',
            'numero_referencia' => 'sometimes|string|max:50',
            'detalles_casa' => 'nullable|string',
            /* 'tipo_material' => 'sometimes|string|max:255', */
            'detalles_adicionales' => 'nullable|string',
            'latitud' => 'sometimes|numeric',
            'longitud' => 'sometimes|numeric',
            'tamano_residuo' => [
                'sometimes',
                Rule::in(collect(TamanoResiduo::cases())->map(fn($case) => $case->value))
            ],
            'tipo_residuo' => [
                'sometimes',
                Rule::in(collect(TipoResiduo::cases())->map(fn($case) => $case->value))
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Error de validación'
            ], 422);
        }

        // Lista de campos que NO se deben permitir modificar
        $noPermitidos = [
            'recolector_id',
            'estado_solicitud',
            'fecha_solicitud',
            'fecha_programada',
            'fecha_recojo'
        ];

        // Eliminar esos campos del request
        $data = collect($request->all())->except($noPermitidos)->toArray();

        // Actualizar solo los campos permitidos
        $solicitud->fill($data);
        $solicitud->save();

        return response()->json([
            'data' => $solicitud->load(['usuario', 'recolector']),
            'message' => 'Solicitud actualizada exitosamente'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $solicitud->delete();

        return response()->json([
            'message' => 'Solicitud eliminada exitosamente'
        ]);
    }
    public function listarPorUsuario($usuarioId)
    {
        // Obtener las solicitudes del usuario con la relación 'recolector'
        $solicitudes = Solicitud::with('recolector')
            ->where('usuario_id', $usuarioId)
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        // Si no hay solicitudes, devolver mensaje de "Usuario no encontrado"
        if ($solicitudes->isEmpty()) {
            return response()->json([
                'message' => 'el Usuario no tiene solicitudes'
            ], 404); // 404 Not Found
        }

        // Obtener los datos del usuario una sola vez
        $usuario = $solicitudes->first()->usuario;

        // Eliminar la relación 'usuario' de cada solicitud para no repetirla
        $solicitudes->each(function ($solicitud) {
            $solicitud->setRelation('usuario', null);
        });

        // Retornar las solicitudes y los datos del usuario una sola vez
        return response()->json([
            'data' => $solicitudes,
            'usuario' => $usuario,  // Datos del usuario una sola vez
            'message' => 'Solicitudes del usuario obtenidas exitosamente'
        ]);
    }


    public function listarPorRecolector($recolectorId)
    {
        // Obtener las solicitudes del recolector con la relación 'usuario' y 'recolector'
        $solicitudes = Solicitud::with('usuario')
            ->where('recolector_id', $recolectorId)
            ->orderBy('fecha_programada', 'desc')
            ->get();

        // Obtener los datos del recolector una sola vez
        $recolector = $solicitudes->isNotEmpty() ? $solicitudes->first()->recolector : null;

        // Eliminar la relación 'recolector' de cada solicitud para no repetirla
        $solicitudes->each(function ($solicitud) {
            $solicitud->setRelation('recolector', null);
        });

        // Retornar las solicitudes y los datos del recolector una sola vez
        return response()->json([
            'data' => $solicitudes,
            'recolector' => $recolector,  // Datos del recolector una sola vez
            'message' => 'Solicitudes del recolector obtenidas exitosamente'
        ]);
    }

    //historiales
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:pendiente,asignada,en_proceso,completada,cancelada',
        ]);

        $solicitud = Solicitud::findOrFail($id);

        $estadoAnterior = $solicitud->estado;
        $solicitud->estado = $request->estado;
        $solicitud->save();

        // Registrar en el historial
        Historial::create([
            /* 'usuario_id' => Auth::id(), // O el ID del admin si viene desde otro lado */
            'usuario_id' => 1,
            'solicitud_id' => $solicitud->id,
            'tipo_evento' => match ($request->estado) {
                'completada' => TipoEventoHistorial::SolicitudCompletada,
                'cancelada' => TipoEventoHistorial::SolicitudCancelada,
                'asignada' => TipoEventoHistorial::SolicitudAsignada,
                default => TipoEventoHistorial::SolicitudCreada, // o un nuevo enum tipo 'EstadoCambiado'
            },
            'detalle' => "Estado cambiado de '{$estadoAnterior}' a '{$request->estado}'",
            'fecha' => now(),
        ]);

        return response()->json([
            'message' => 'Estado actualizado correctamente.',
            'solicitud' => $solicitud,
        ]);
    }




    public function updateHistorialAdmin(Request $request, string $id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'sometimes|exists:users,id',
            'nombre' => 'required|string|max:255', 
            'carnet' => 'required|string|max:20',
            'recolector_id' => 'sometimes|exists:recolectores,id',
            'direccion_recojo' => 'sometimes|string|max:255',
            'numero_referencia' => 'sometimes|string|max:50',
            'detalles_casa' => 'nullable|string',
            /* 'tipo_material' => 'sometimes|string|max:255', */
            'detalles_adicionales' => 'nullable|string',
            'estado_solicitud' => [
                'sometimes',
                Rule::in(collect(TipoEventoSolicitud::cases())->pluck('value'))
            ],
            'fecha_solicitud' => 'sometimes|date',
            'fecha_programada' => 'sometimes|date|nullable',
            'fecha_recojo' => 'sometimes|date|nullable',
            'latitud' => 'sometimes|numeric',
            'longitud' => 'sometimes|numeric',
            'tamano_residuo' => [
                'sometimes',
                Rule::in(collect(TamanoResiduo::cases())->pluck('value'))
            ],
            'tipo_residuo' => [
                'sometimes',
                Rule::in(collect(TipoResiduo::cases())->pluck('value'))
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Error de validación'
            ], 422);
        }

        $data = $validator->validated();

            $user = Auth::guard('sanctum')->user();
    if ($user) {
        // Esto sobreescribe cualquier usuario_id que haya mandado el cliente
        $data['usuario_id'] = $user->id;
    }


        // Guardar los valores originales para comparar después
        $original = $solicitud->only(array_keys($data));

        // Actualizar la solicitud
        $solicitud->fill($data);
        $solicitud->save();

        // Detectar cambios
        $cambios = [];






        /* foreach ($data as $key => $nuevoValor) { */
        /*     $valorAnterior = $original[$key] ?? null; */
        /*     if ($valorAnterior != $nuevoValor) { */
        /*         $cambios[] = "$key: '$valorAnterior' → '$nuevoValor'"; */
        /*     } */
        /* } */

        function stringify($valor) {
    if (is_null($valor)) return 'null';
    if (is_bool($valor)) return $valor ? 'true' : 'false';
    if (is_object($valor)) {
        if (method_exists($valor, '__toString')) return (string) $valor;
        if (method_exists($valor, 'value')) return $valor->value;
        return get_class($valor); // fallback: nombre de clase
    }
    if (is_array($valor)) return json_encode($valor);
    return (string) $valor;
}

$cambios = [];

foreach ($data as $key => $nuevoValor) {
    $valorAnterior = $original[$key] ?? null;
    if ($valorAnterior != $nuevoValor) {
        $cambios[] = "$key: '" . stringify($valorAnterior) . "' → '" . stringify($nuevoValor) . "'";
    }
}


        // Solo guardar historial si hubo cambios
if (count($cambios)) {

                    $tipoEvento = TipoEventoSolicitud::from($data['estado_solicitud'])->value;

            Historial::create([
                'usuario_id'   =>  $user?->id,
                'solicitud_id' => $solicitud->id,
                'tipo_evento'  => $tipoEvento,
                'detalle'      => 'Cambios realizados: ' . implode('; ', $cambios),
                'fecha'        => now(),
            ]);
        }

        return response()->json([
            'data' => $solicitud->load(['usuario', 'recolector']),
            'message' => 'Solicitud actualizada exitosamente'
        ]);
    }



    // listar historial de una solicitud
    public function getHistorial($id)
    {
        // Buscar la solicitud con el ID proporcionado
        $solicitud = Solicitud::find($id);

        // Si no se encuentra la solicitud, retornamos un error 404
        if (!$solicitud) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        // Obtener todos los historiales relacionados con esa solicitud
        $historiales = $solicitud->historiales; // Relación definida en el modelo

        // Si no hay historiales, podemos devolver un mensaje indicativo
        if ($historiales->isEmpty()) {
            return response()->json([
                'message' => 'No hay historiales registrados para esta solicitud.'
            ], 404);
        }

        // Retornar el historial de la solicitud
        return response()->json([
            'data' => $historiales,
            'message' => 'Historial de solicitud obtenido exitosamente'
        ]);
    }
}
