<?php


namespace App\Enums;

enum TamanoResiduo: string
{
    case PEQUENO = 'pequeño';
    case MEDIANO = 'mediano';
    case GRANDE = 'grande';

    public function descripcion(): string
    {
        return match($this) {
            self::PEQUENO => 'Pequeño (1-5 kg) - Bolsa de basura regular',
            self::MEDIANO => 'Mediano (5-20 kg) - Varias bolsas',
            self::GRANDE => 'Grande (+20 kg) - Necesita transporte especial',
        };
    }
}
