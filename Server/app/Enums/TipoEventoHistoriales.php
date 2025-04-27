<?php

namespace App\Enums;

enum TipoEventoHistoriales: string
{
    case SolicitudCreada = 'solicitud_creada';
    case SolicitudAsignada = 'solicitud_asignada';
    case SolicitudCompletada = 'solicitud_completada';
    case SolicitudCancelada = 'solicitud_cancelada';
}

