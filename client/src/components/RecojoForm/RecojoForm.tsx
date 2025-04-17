import React, { useState, useEffect } from 'react';
import { RecojoFormData } from '../../types/recojoTypes';

interface RecojoFormProps {
  onSubmit: (data: RecojoFormData) => void;
  address: string;
}

const RecojoForm: React.FC<RecojoFormProps> = ({ onSubmit, address }) => {
  const [formData, setFormData] = useState<RecojoFormData & { carnet: string; nombreCompleto: string }>({
    direccion: address,
    detallesCasa: '',
    tipoResiduo: 'orgánico',
    tamañoResiduo: 'pequeño',
    referencia: '',
    carnet: '',
    nombreCompleto: ''
  });

  useEffect(() => {
    setFormData((prevData) => ({ ...prevData, direccion: address }));
  }, [address]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(formData);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;

    if (name === 'carnet' || name === 'referencia') {
      const numericValue = value.replace(/[^0-9]/g, '');
      if (name === 'referencia' && numericValue.length > 0 && !/^[67]/.test(numericValue)) {
        return;
      }
      setFormData({
        ...formData,
        [name]: numericValue
      });
    } else if (name === 'nombreCompleto') {
      const letterValue = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
      setFormData({
        ...formData,
        [name]: letterValue
      });
    } else {
      setFormData({
        ...formData,
        [name]: value
      });
    }
  };

  return (
    <div className="w-full max-w-[500px] mx-auto p-4">
      <h2 className="text-black text-xl mb-6 uppercase text-left font-normal">SOLICITUD DE RECOJO</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-5">
        <div className="flex flex-col gap-2">
          <label htmlFor="nombreCompleto" className="text-gray-700 text-sm font-normal">Nombre Completo:</label>
          <input
            type="text"
            id="nombreCompleto"
            name="nombreCompleto"
            value={formData.nombreCompleto}
            onChange={handleChange}
            required
            placeholder="Ej: Juan Pérez"
            maxLength={50}
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="carnet" className="text-gray-700 text-sm font-normal">Carnet:</label>
          <input
            type="text"
            id="carnet"
            name="carnet"
            value={formData.carnet}
            onChange={handleChange}
            required
            placeholder="Ej: 12345678"
            maxLength={15}
            pattern="\d*"
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="direccion" className="text-gray-700 text-sm font-normal">Dirección:</label>
          <input
            type="text"
            id="direccion"
            name="direccion"
            value={formData.direccion}
            onChange={handleChange}
            required
            placeholder="Ej: Av. Los Álamos 123, Distrito"
            maxLength={60}
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="detallesCasa" className="text-gray-700 text-sm font-normal">Detalles de la casa:</label>
          <input
            type="text"
            id="detallesCasa"
            name="detallesCasa"
            value={formData.detallesCasa}
            onChange={handleChange}
            required
            placeholder="Ej: Casa azul de 2 pisos, reja negra"
            maxLength={100}
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="tipoResiduo" className="text-gray-700 text-sm font-normal">Tipo de residuo:</label>
          <select
            id="tipoResiduo"
            name="tipoResiduo"
            value={formData.tipoResiduo}
            onChange={handleChange}
            required
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 appearance-none focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
            style={{ backgroundImage: "url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%234A5568%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27/%3e%3c/svg%3e')", backgroundRepeat: 'no-repeat', backgroundPosition: 'right 1rem center', backgroundSize: '1.5em' }}
          >
            <option value="peligroso">Residuos Peligrosos</option>
            <option value="electrico">Residuos Eléctricos y Electrónicos</option>
            <option value="vidrio">Vidrio</option>
            <option value="metales">Metales</option>
            <option value="papel">Papel y Cartón</option>
            <option value="plasticos">Plásticos</option>
            <option value="organico">Materia Orgánica</option>
          </select>
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="tamañoResiduo" className="text-gray-700 text-sm font-normal">Tamaño de residuo:</label>
          <select
            id="tamañoResiduo"
            name="tamañoResiduo"
            value={formData.tamañoResiduo}
            onChange={handleChange}
            required
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 appearance-none focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
            style={{ backgroundImage: "url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%234A5568%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27/%3e%3c/svg%3e')", backgroundRepeat: 'no-repeat', backgroundPosition: 'right 1rem center', backgroundSize: '1.5em' }}
          >
            <option value="pequeño">Pequeño (1-5 kg) - Bolsa de basura regular</option>
            <option value="mediano">Mediano (5-20 kg) - Varias bolsas</option>
            <option value="grande">Grande (+20 kg) - Necesita transporte especial</option>
          </select>
        </div>

        <div className="flex flex-col gap-2">
          <label htmlFor="referencia" className="text-gray-700 text-sm font-normal">Número de referencia:</label>
          <input
            type="text"
            id="referencia"
            name="referencia"
            value={formData.referencia}
            onChange={handleChange}
            required
            placeholder="Ej: 70707070"
            maxLength={8}
            className="p-4 border-2 border-gray-300 rounded-xl bg-gray-100 text-sm w-full text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <button
          type="submit"
          className="!bg-green-500 text-white border-none py-2 px-6 rounded-xl cursor-pointer text-sm w-auto self-start mt-2 hover:bg-green-600 transition-colors"
        >
          Enviar
        </button>

        <p className="text-gray-600 text-xs mt-2 leading-snug">
          Rellena todos los espacios<br />
          para enviar tu solicitud
        </p>
      </form>
    </div>
  );
};

export default RecojoForm;