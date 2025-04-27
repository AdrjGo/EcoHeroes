<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitudSeeder extends Seeder
{
    public function run(): void
    {
        Solicitud::create([
            'usuario_id' => 1,
            'recolector_id' => 1,
            'direccion_recojo' => 'Calle 5 #123',
            'numero_referencia' => 'REF-001',
            'detalles_casa' => 'Casa verde con portón negro',
            'tipo_material' => 'organico',
            'detalles_adicionales' => 'Pasar antes de las 9am',
            'estado' => 'pendiente',
            'fecha_programada' => now()->addDays(2),
            'fecha_recojo' => null,
            'latitud' => -17.78629,
            'longitud' => -63.18117,
            'tamano_residuo' => 'mediano',
            'tipo_residuo' => 'organico',
        ]);
    }
}