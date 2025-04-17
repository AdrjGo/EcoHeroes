import React, { useState } from 'react';
import Map from '../components/Map/Map';
import RecojoForm from '../components/RecojoForm/RecojoForm';
import { RecojoFormData } from '../types/recojoTypes';
import styles from './Solicitud.module.css';

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
    <div className={styles.container}>
      <div className={styles.content}>
        <RecojoForm onSubmit={handleSubmit} address={address} />
        <Map onLocationSelect={handleLocationSelect} />
      </div>
    </div>
  );
};

export default SolicitudPage;