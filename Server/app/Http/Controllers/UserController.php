<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $Usuario = User::with('roles')->get();  // Obtener todos los Users con sus roles
        return response()->json($Usuario);
    }

    public function show($id)
    {
        $User = User::with('roles')->findOrFail($id);  // Obtener un User por ID con sus roles
        return response()->json($User);
    }

    public function store(Request $request)
    {
        $User = User::create($request->only('nombre', 'email', 'contraseña', 'telefono'));
        $User->roles()->attach($request->roles);  // Asociar roles al User
        return response()->json($User, 201);
    }

    public function update(Request $request, $id)
    {
        $User = User::findOrFail($id);
        $User->update($request->only('nombre', 'email', 'contraseña', 'telefono'));
        $User->roles()->sync($request->roles);  
        return response()->json($User);
    }

    public function destroy($id)
    {
        $User = User::findOrFail($id);
        $User->delete();
        return response()->json(null, 204);
    }
}
