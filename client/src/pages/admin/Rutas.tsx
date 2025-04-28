import React, { useState, useCallback } from 'react';
import { Calendar, dateFnsLocalizer, View, Components, EventWrapperProps, DateCellWrapperProps } from 'react-big-calendar';
import { format, parse, getDay } from 'date-fns';
import {es} from 'date-fns/locale';
import 'react-big-calendar/lib/css/react-big-calendar.css';
// import Map from '../../components/Map/Map';
import { solicitudService } from '../../services/solicitudService';
import { Marker, Popup, useMapEvents } from 'react-leaflet';
import L from 'leaflet';
import { MapContainer, TileLayer } from 'react-leaflet';
import locationIcon from '../../assets/icons/location-marker.svg';

const locales = {
  'es': es,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek: () => 1,
  getDay: (date: Date) => {
    const day = getDay(date);
    return day === 0 ? 6 : day - 1;
  },
  locales,
});

// Define the 'Evento' interface
interface Evento {
  id: number;
  title: string;
  start: Date;
  end: Date;
  ubicacion?: {
    lat: number;
    lng: number;
  };
}

interface Solicitud {
  id: number;
  nombre: string;
  direccion_recojo: string;
  latitud: string;
  longitud: string;
  numero_referencia: string;
  tipo_residuo: string;
}

const pickupLocationIcon = new L.Icon({
  iconUrl: locationIcon,
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32],
  className: "opacity-90 filter hue-rotate-0"
});

function MapClickHandler({ onLocationSelect }: { onLocationSelect: ([lat, lng]: [number, number]) => void }) {
  useMapEvents({
    click: (e) => {
      onLocationSelect([e.latlng.lat, e.latlng.lng]);
    },
  });
  return null;
}

const Rutas = () => {
  const [events, setEvents] = useState<Evento[]>([]);
  const [view, setView] = useState<View>('month');
  const [date, setDate] = useState(new Date());
  const [selectedEvent, setSelectedEvent] = useState<Evento | null>(null);
  const [solicitudes, setSolicitudes] = useState<Solicitud[]>([]);

  useEffect(() => {
    const fetchSolicitudes = async () => {
      try {
        const res = await solicitudService.obtenerSolicitudes();
        if (res.data && res.data.data) {
          setSolicitudes(res.data.data);
        }
      } catch {
        setSolicitudes([]);
      }
    };
    fetchSolicitudes();
  }, []);

  const handleLocationSelect = ([lat, lng]: [number, number]) => {
    if (selectedEvent) {
      setEvents(prevEvents => 
        prevEvents.map(event => 
          event.id === selectedEvent.id 
            ? { ...event, ubicacion: { lat, lng } }
            : event
        )
      );
    }
  };

  return (
    <div className="p-8 bg-gradient-to-br from-green-50 via-white to-green-50 min-h-screen">
      <div className="mb-8">
        <h1 className="text-4xl font-bold text-gray-800 mb-2">
          Calendario de Rutas de Recojo
        </h1>
        <p className="text-gray-600 text-lg">Gestiona y visualiza todas las rutas programadas</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div className="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-green-200">
          <style>
            {`
              .rbc-calendar {
                background-color: white;
                border-radius: 12px;
                overflow: hidden;
              }
              .rbc-month-view {
                border: 1px solid #E5E7EB40 !important;
                background-color: white;
                border-radius: 12px;
                overflow: hidden;
              }
              .rbc-month-row {
                border: none !important;
                min-height: 110px !important;
              }
              .rbc-month-row + .rbc-month-row {
                border-top: 1px solid #E5E7EB40 !important;
              }
              .rbc-day-bg {
                border-right: 1px solid #E5E7EB40 !important;
              }
              .rbc-day-bg:last-child {
                border-right: none !important;
              }
              .rbc-header {
                font-weight: 500 !important;
                padding: 12px !important;
                text-transform: uppercase !important;
                font-size: 0.8rem !important;
                color: #374151 !important;
                background-color: #F0FDF4 !important;
                border-bottom: 1px solid #E5E7EB40 !important;
                text-align: center !important;
              }
              .rbc-header + .rbc-header {
                border-left: 1px solid #E5E7EB40 !important;
              }
              .rbc-date-cell {
                text-align: left !important;
                padding: 8px !important;
                font-size: 0.85rem !important;
                color: #374151 !important;
                position: relative !important;
              }
              .rbc-date-cell > a {
                padding-left: 8px !important;
                padding-top: 4px !important;
              }
              .rbc-off-range {
                color: #9CA3AF !important;
              }
              .rbc-off-range-bg {
                background-color: white !important;
              }
              .rbc-today {
                background-color: #F0FDF4 !important;
              }
              .rbc-now {
                position: relative !important;
              }
              .rbc-now .rbc-button-link {
                position: absolute !important;
                left: 8px !important;
                top: 4px !important;
                background-color: #059669 !important;
                color: white !important;
                width: 24px !important;
                height: 24px !important;
                border-radius: 50% !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-weight: 500 !important;
              }
              .rbc-event {
                background-color: #059669 !important;
                border: none !important;
                border-radius: 4px !important;
                padding: 2px 4px !important;
                font-size: 0.75rem !important;
                margin: 1px 2px !important;
              }
              .rbc-event-content {
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                font-weight: 500 !important;
              }
              .rbc-row-segment {
                padding: 0 1px !important;
              }
              .rbc-show-more {
                color: #059669 !important;
                font-size: 0.75rem !important;
                font-weight: 500 !important;
                padding: 2px 4px !important;
                background: transparent !important;
              }
              .rbc-row-content {
                margin-top: 28px !important;
              }
              .rbc-toolbar {
                margin-bottom: 1rem !important;
              }
              .rbc-toolbar button {
                color: #374151 !important;
                border: 1px solid #E5E7EB !important;
                padding: 6px 12px !important;
                font-size: 0.875rem !important;
                border-radius: 6px !important;
                background-color: white !important;
              }
              .rbc-toolbar button.rbc-active {
                background-color: #059669 !important;
                color: white !important;
                border-color: #059669 !important;
              }
              .rbc-toolbar-label {
                font-size: 1rem !important;
                font-weight: 500 !important;
                color: #374151 !important;
              }
              @media (max-width: 768px) {
                .rbc-calendar {
                  height: 60vh !important;
                }
                .rbc-toolbar {
                  flex-direction: column !important;
                  align-items: center !important;
                }
                .rbc-toolbar button {
                  margin-bottom: 0.5rem !important;
                }
              }
            `}
          </style>
          <div style={{ height: '70vh', display: 'flex', justifyContent: 'center', alignItems: 'flex-start', paddingTop: '1rem' }}>
            <Calendar
              localizer={localizer}
              events={events}
              startAccessor="start"
              endAccessor="end"
              style={{ height: '70vh', width: '100%' }}
              view={view}
              onView={setView}
              date={date}
              onNavigate={setDate}
              onSelectEvent={(event) => setSelectedEvent(event)}
            />
          </div>
        </div>

        {/* Mapa y Detalles */}
        <div className="space-y-6">
          <div className="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-green-200">
            <h2 className="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-200">
              Ubicación de Rutas
            </h2>
            <MapContainer center={[-17.3935, -66.1570]} zoom={12} className="w-full h-full min-h-[500px] rounded-lg z-0">
              <MapClickHandler onLocationSelect={handleLocationSelect} />
              <TileLayer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
              />
              {solicitudes.map((solicitud) => (
                <Marker
                  key={solicitud.id}
                  position={[parseFloat(solicitud.latitud), parseFloat(solicitud.longitud)]}
                  icon={pickupLocationIcon}
                >
                  <Popup>
                    <div>
                      <strong>{solicitud.nombre}</strong>
                      <p>{solicitud.direccion_recojo}</p>
                      <p>Celular: {solicitud.numero_referencia}</p>
                      <p>Tipo de residuo: {solicitud.tipo_residuo}</p>
                    </div>
                  </Popup>
                </Marker>
              ))}
            </MapContainer>
          </div>
          
          {selectedEvent && (
            <div className="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-green-200">
              <div className="space-y-6">
                <div className="flex items-center justify-between border-b-2 border-green-200 pb-4">
                  <h3 className="text-2xl font-bold text-gray-800">{selectedEvent.title}</h3>
                  <span className="px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-bold border border-green-300">
                    Activa
                  </span>
                </div>
                
                <div className="flex items-start space-x-4 text-gray-700">
                  <div className="bg-green-100 p-3 rounded-xl border border-green-300">
                    <svg className="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div className="flex-1">
                    <p className="font-bold text-gray-800 mb-1">Horario</p>
                    <p className="text-sm bg-green-50 p-2 rounded-lg border border-green-200">
                      {format(selectedEvent.start, 'PPpp', { locale: es })} - {format(selectedEvent.end, 'p', { locale: es })}
                    </p>
                  </div>
                </div>

                {selectedEvent.ubicacion && (
                  <div className="flex items-start space-x-4 text-gray-700">
                    <div className="bg-green-100 p-3 rounded-xl border border-green-300">
                      <svg className="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </div>
                    <div className="flex-1">
                      <p className="font-bold text-gray-800 mb-1">Ubicación</p>
                      <div className="space-y-1">
                        <p className="text-sm bg-green-50 p-2 rounded-lg border border-green-200">
                          Latitud: {selectedEvent.ubicacion.lat.toFixed(6)}
                        </p>
                        <p className="text-sm bg-green-50 p-2 rounded-lg border border-green-200">
                          Longitud: {selectedEvent.ubicacion.lng.toFixed(6)}
                        </p>
                      </div>
                    </div>
                  </div>
                )}

                <div className="flex gap-4 mt-6 pt-4 border-t-2 border-green-200">
                  <button className="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold">
                    Editar Ruta
                  </button>
                  <button className="flex-1 px-6 py-3 bg-white text-gray-700 border-2 border-green-300 rounded-xl hover:bg-green-50 hover:border-green-400 transition-all duration-300 shadow-lg hover:shadow-xl font-bold">
                    Ver Detalles
                  </button>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Rutas; 