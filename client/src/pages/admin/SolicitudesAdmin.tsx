import React from 'react';
import TablaSolicitudes from '../../components/admin/TablaSolicitudes';

const SolicitudesAdmin: React.FC = () => {
  return (
    <div className="py-12 px-16 bg-gradient-to-br from-green-50 via-white to-green-50 min-h-screen flex flex-col items-center">
      <h1 className="text-3xl font-bold mb-8 text-green-700">Solicitudes de Recojo</h1>
      <div className="w-full">
        <TablaSolicitudes />
      </div>
    </div>
  );
};

export default SolicitudesAdmin; 