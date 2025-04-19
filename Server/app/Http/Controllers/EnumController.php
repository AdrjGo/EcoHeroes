<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Enums\TipoResiduo;
use App\Enums\TamanoResiduo;

class EnumController extends Controller
{
    public function tiposResiduo()
    {
        $tipos = collect(TipoResiduo::cases())->map(function ($tipo) {
            return [
                'value' => $tipo->value,
                'descripcion' => $tipo->descripcion(),
            ];
        });

        return response()->json($tipos);
    }


    public function tamanosResiduo()
    {
        $tamanos = collect(TamanoResiduo::cases())->map(function ($tamano) {
            return [
                'value' => $tamano->value,
                'descripcion' => $tamano->descripcion(),
            ];
        });

        return response()->json($tamanos);
    }

}

