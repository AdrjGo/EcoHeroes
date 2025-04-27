import React, { useState } from 'react';
import Map from '../components/Map/Map';
import RecojoForm from '../components/RecojoForm/RecojoForm';
import { RecojoFormData } from '../types/recojoTypes';
import { solicitudService } from '../services/solicitudService';
import { AxiosError } from 'axios';

interface Location {
  lat: number;
  lng: number;
}

const SolicitudPage: React.FC = () => {
  const [selectedLocation, setSelectedLocation] = useState<Location | null>(null);
  const [address, setAddress] = useState<string>('');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState(false);

  const handleSubmit = async (data: RecojoFormData) => {
    try {
      setIsLoading(true);
      setError(null);
      
      console.log('Datos recibidos del formulario:', data);
      console.log('Valor de tipoResiduo antes de transformar:', data.tipoResiduo);
      
      // Transformar los datos para que coincidan con lo que espera el backend
      const solicitudData = {
        nombre: data.nombreCompleto,
        direccion_recojo: data.direccion,
        detalles_casa: data.detallesCasa,
        tipo_residuo: data.tipoResiduo,
        tamano_residuo: data.tamañoResiduo.toLowerCase().trim(),
        numero_referencia: data.referencia,
        carnet: data.carnet,
        latitud: selectedLocation?.lat,
        longitud: selectedLocation?.lng,
        fecha_solicitud: new Date().toISOString()
      };

      console.log('Datos a enviar:', solicitudData);
      console.log('Valor exacto de tipo_residuo:', solicitudData.tipo_residuo);

      await solicitudService.crearSolicitud(solicitudData);
      
      setSuccess(true);
      // Limpiar el formulario después de 3 segundos
      setTimeout(() => {
        setSuccess(false);
        setSelectedLocation(null);
        setAddress('');
      }, 3000);
    } catch (error) {
      const axiosError = error as AxiosError;
      console.error('Error completo:', axiosError.response?.data || error);
      setError(axiosError.message || 'Error al enviar la solicitud. Por favor, inténtalo de nuevo.');
    } finally {
      setIsLoading(false);
    }
  };

  const handleLocationSelect = async (location: Location) => {
    setSelectedLocation(location);
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${location.lat}&lon=${location.lng}&format=json`);
    const data = await response.json();
    if (data && data.display_name) {
      setAddress(data.display_name);
    }
  };

  return (
    <div className="flex-1 flex flex-col items-center p-8 bg-gray-100 min-h-0">
      <div className="w-full max-w-[1200px] grid grid-cols-1 md:grid-cols-2 gap-8 mx-auto min-h-0 items-start">
        <div>
          <RecojoForm onSubmit={handleSubmit} address={address} />
          {isLoading && (
            <div className="mt-4 text-green-500">
              Enviando solicitud...
            </div>
          )}
          {error && (
            <div className="mt-4 text-red-500">
              {error}
            </div>
          )}
          {success && (
            <div className="mt-4 text-green-500">
              ¡Solicitud enviada con éxito!
            </div>
          )}
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-green-200 mt-35">
          <div className="relative">
            <div className="absolute top-0 left-0 w-full text-center text-black text-lg z-10 mt-[-20px] bg-white px-4 py-2 rounded-t-lg border-b-2 border-green-200 font-bold">
              Selecciona tu ubicación
            </div>
            <Map onLocationSelect={handleLocationSelect} />
          </div>
        </div>
      </div>
    </div>
  );
};

export default SolicitudPage;