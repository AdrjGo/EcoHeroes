import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, Marker, Popup, useMap, useMapEvents } from 'react-leaflet';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import styles from './Map.module.css';
import locationIcon from '../../assets/icons/location-marker.svg';

// Configuración del ícono para la ubicación actual
const currentLocationIcon = new L.Icon({
  iconUrl: locationIcon,
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32],
  className: styles.pulsingIcon
});

// Configuración del ícono para el punto de recolección
const pickupLocationIcon = new L.Icon({
  iconUrl: locationIcon,
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32],
  className: styles.pickupIcon
});

// Componente para manejar los clics en el mapa
function MapClickHandler({ onLocationSelect }: { onLocationSelect: (latlng: [number, number]) => void }) {
  useMapEvents({
    click: (e) => {
      onLocationSelect([e.latlng.lat, e.latlng.lng]);
    },
  });
  return null;
}

interface MapProps {
  onLocationSelect?: (location: { lat: number; lng: number }) => void;
}

const Map: React.FC<MapProps> = ({ onLocationSelect }) => {
  const [currentPosition, setCurrentPosition] = useState<[number, number]>([4.7109, -74.0721]);
  const [selectedPosition, setSelectedPosition] = useState<[number, number] | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string>('');
  const [accuracy, setAccuracy] = useState<number | null>(null);

  useEffect(() => {
    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(
        (location) => {
          setCurrentPosition([location.coords.latitude, location.coords.longitude]);
          setAccuracy(location.coords.accuracy);
          setLoading(false);
        },
        (error) => {
          console.error('Error getting location:', error);
          setError('No se pudo obtener tu ubicación');
          setLoading(false);
        },
        {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        }
      );
    } else {
      setError('Tu navegador no soporta geolocalización');
      setLoading(false);
    }
  }, []);

  const handleLocationSelect = (latlng: [number, number]) => {
    setSelectedPosition(latlng);
    if (onLocationSelect) {
      onLocationSelect({ lat: latlng[0], lng: latlng[1] });
    }
  };

  return (
    <div className={styles.mapContainer}>
      {loading ? (
        <div className={styles.loading}>
          <div className={styles.loadingSpinner}></div>
          Obteniendo tu ubicación...
        </div>
      ) : error ? (
        <div className={styles.error}>{error}</div>
      ) : (
        <>
          <div className={styles.instructions}>
            Haz clic en el mapa para seleccionar el punto de recolección
          </div>
          <MapContainer center={currentPosition} zoom={15} className={styles.map}>
            <MapClickHandler onLocationSelect={handleLocationSelect} />
            <TileLayer
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
              attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            />
            {/* Marcador del punto seleccionado */}
            {selectedPosition && (
              <Marker position={selectedPosition} icon={pickupLocationIcon}>
                <Popup>
                  <div className={styles.popup}>
                    <strong>Punto de recolección</strong>
                    <p className={styles.coordinates}>
                      Lat: {selectedPosition[0].toFixed(6)}<br />
                      Lng: {selectedPosition[1].toFixed(6)}
                    </p>
                  </div>
                </Popup>
              </Marker>
            )}
          </MapContainer>
        </>
      )}
    </div>
  );
};

export default Map;