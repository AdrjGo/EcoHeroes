<?php

namespace App\Http\Controllers;

use App\Models\Recolector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RecolectorController extends Controller
{
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

            return response()->json([
                'success' => true,
                'data' => $recolectores,
                'message' => 'Lista de recolectores obtenida exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
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
                'apellido' => 'nullable|string|max:255',
                'ci' => 'required|string|unique:recolectores,ci|max:20',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|unique:recolectores,email',
                'direccion' => 'required|string|max:255',
                'licencia' => 'required|string|max:50',
                'estado' => 'nullable|string|in:activo,inactivo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }

            // Establecer valores predeterminados
            $datos = $request->all();
            if (!isset($datos['estado'])) {
                $datos['estado'] = 'activo';
            }

            $recolector = Recolector::create($datos);

            return response()->json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
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

            return response()->json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Detalles del recolector obtenidos'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
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
                'nombre' => 'sometimes|required|string|max:255',
                'apellido' => 'sometimes|nullable|string|max:255',
                'ci' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('recolectores')->ignore($id)
                ],
                'telefono' => 'sometimes|required|string|max:20',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('recolectores')->ignore($id)
                ],
                'direccion' => 'sometimes|required|string|max:255',
                'licencia' => 'sometimes|required|string|max:50',
                'estado' => 'sometimes|required|in:activo,inactivo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }

            // Actualización directa campo por campo para asegurarnos que se apliquen los cambios
            foreach ($request->all() as $key => $value) {
                if (in_array($key, $recolector->getFillable())) {
                    $recolector->$key = $value;
                }
            }

            $recolector->save();

            // Recargar el modelo para asegurar que devolvemos los datos actualizados
            $recolector = $recolector->fresh();

            return response()->json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector actualizado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
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

            // Cambio de estado en lugar de eliminar físicamente
            $recolector->estado = 'inactivo';
            $recolector->save();

            return response()->json([
                'success' => true,
                'message' => 'Recolector marcado como inactivo exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
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
                return response()->json([
                    'success' => false,
                    'message' => 'El recolector ya está activo'
                ], 422);
            }

            $recolector->estado = 'activo';
            $recolector->save();

            return response()->json([
                'success' => true,
                'data' => $recolector,
                'message' => 'Recolector restaurado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recolector no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al restaurar el recolector: ' . $e->getMessage()
            ], 500);
        }
    }
}
