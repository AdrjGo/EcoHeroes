<?php

namespace App\Http\Controllers;

use App\Models\Recolector;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class RecolectorController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
        // Solo pedimos autenticación para todos los métodos excepto 'index'
        $this->middleware('auth:sanctum')->except(['index']);

        // Y de paso, solo admins o moderadores pueden usar los demás métodos (excepto index)
        $this->middleware('isAdminOrModerator')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $estado = $request->query('estado');

            $query = Recolector::query()->latest();

            if ($estado) {
                $query->where('estado', $estado);
            }

            $recolectores = $query->paginate($perPage);

            return Response::json([
                'success' => true,
                'data' => $recolectores,
                'message' => 'Lista de recolectores obtenida exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al obtener los recolectores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'ci' => 'required|string|unique:recolectores,ci',
                'telefono' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:recolectores,email',
                'direccion' => 'nullable|string',
                'licencia' => ['nullable', Rule::in(['A', 'B', 'C', 'P'])],
                'estado' => 'required|string|in:activo,inactivo',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }

            $data = $request->all();

            if ($request->hasFile('foto')) {
                $uploadResult = $this->cloudinary->upload($request->file('foto'));
                if ($uploadResult['success']) {
                    $data['foto'] = $uploadResult['url'];
                } else {
                    return Response::json([
                        'success' => false,
                        'message' => 'Error al subir la imagen: ' . $uploadResult['message']
                    ], 500);
                }
            }

            $recolector = Recolector::create($data);

            return Response::json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al crear el recolector: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $recolector = Recolector::findOrFail($id);

            return Response::json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Detalles del recolector obtenidos'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al obtener el recolector: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $recolector = Recolector::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|string|max:255',
                'apellido' => 'sometimes|string|max:255',
                'ci' => ['sometimes', 'string', Rule::unique('recolectores')->ignore($id)],
                'telefono' => 'nullable|string|max:20',
                'email' => ['nullable', 'email', Rule::unique('recolectores')->ignore($id)],
                'direccion' => 'nullable|string',
                'licencia' => ['nullable', Rule::in(['A', 'B', 'C', 'P'])],
                'estado' => 'sometimes|string|in:activo,inactivo',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }

            $data = $request->all();

            if ($request->hasFile('foto')) {
                // Subir nueva imagen
                $uploadResult = $this->cloudinary->upload($request->file('foto'));
                if ($uploadResult['success']) {
                    $data['foto'] = $uploadResult['url'];
                } else {
                    return Response::json([
                        'success' => false,
                        'message' => 'Error al subir la imagen: ' . $uploadResult['message']
                    ], 500);
                }
            }

            $recolector->update($data);

            return Response::json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector actualizado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al actualizar el recolector: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $recolector = Recolector::findOrFail($id);

            // Eliminar la foto si existe
            if ($recolector->foto) {
                $path = str_replace(URL::asset('storage/'), '', $recolector->foto);
                Storage::disk('public')->delete($path);
            }

            $recolector->delete();

            return Response::json([
                'success' => true,
                'message' => 'Recolector eliminado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al eliminar el recolector: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurar un recolector inactivo a activo.
     */
    public function restore(string $id)
    {
        try {
            $recolector = Recolector::findOrFail($id);

            if ($recolector->estado == 'activo') {
                return Response::json([
                    'success' => false,
                    'message' => 'El recolector ya está activo'
                ], 422);
            }

            $recolector->estado = 'activo';
            $recolector->save();

            return Response::json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector restaurado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al restaurar el recolector: ' . $e->getMessage()
            ], 500);
        }
    }
    //solicitudes de cada recolector
/*     public function listarPorRecolector($recolectorId) */
/* { */
/*     $solicitudes = Solicitud::where('recolector_id', $recolectorId) */
/*         ->orderBy('fecha_programada', 'desc') */
/*         ->get(); */
/**/
/*     return response()->json([ */
/*         'data' => $solicitudes, */
/*         'message' => 'Solicitudes del recolector obtenidas exitosamente' */
/*     ]); */
/* } */
/**/
}
