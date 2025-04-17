import React, { useState } from 'react';
import Map from '../components/Map/Map';
import RecojoForm from '../components/RecojoForm/RecojoForm';
import { RecojoFormData } from '../types/recojoTypes';

interface Location {
  lat: number;
  lng: number;
}

const SolicitudPage: React.FC = () => {
  const [selectedLocation, setSelectedLocation] = useState<Location | null>(null);
  const [address, setAddress] = useState<string>('');

  const handleSubmit = (data: RecojoFormData) => {
    console.log('Datos enviados:', {
      ...data,
      ubicacion: selectedLocation
    });
    // Aquí iría la conexión con tu backend
  };

  const handleLocationSelect = async (location: Location) => {
    setSelectedLocation(location);
    console.log('Ubicación seleccionada:', location);
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${location.lat}&lon=${location.lng}&format=json`);
    const data = await response.json();
    if (data && data.display_name) {
      setAddress(data.display_name);
    }
  };

  return (
    <div className="flex-1 flex flex-col items-center p-8 bg-gray-100 min-h-0">
      <div className="w-full max-w-[1200px] grid grid-cols-1 md:grid-cols-2 gap-8 mx-auto min-h-0 items-start">
        <RecojoForm onSubmit={handleSubmit} address={address} />
        <Map onLocationSelect={handleLocationSelect} />
      </div>
    </div>
  );
};

export default SolicitudPage;