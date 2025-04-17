import React, { useState, useEffect } from 'react';
import { RecojoFormData } from '../../types/recojoTypes';
import styles from './RecojoForm.module.css';

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
      // Allow only numbers
      const numericValue = value.replace(/[^0-9]/g, '');
      if (name === 'referencia' && numericValue.length > 0 && !/^[67]/.test(numericValue)) {
        return; // Do not update if the first digit is not 6 or 7
      }
      setFormData({
        ...formData,
        [name]: numericValue
      });
    } else if (name === 'nombreCompleto') {
      // Allow only letters
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
    <div className={styles.formContainer}>
      <h2 className={styles.title}>SOLICITUD DE RECOJO</h2>
      <form onSubmit={handleSubmit} className={styles.form}>
        <div className={styles.formGroup}>
          <label htmlFor="nombreCompleto">Nombre Completo:</label>
          <input
            type="text"
            id="nombreCompleto"
            name="nombreCompleto"
            value={formData.nombreCompleto}
            onChange={handleChange}
            required
            placeholder="Ej: Juan Pérez"
            maxLength={50}
          />
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="carnet">Carnet:</label>
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
          />
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="direccion">Dirección:</label>
          <input
            type="text"
            id="direccion"
            name="direccion"
            value={formData.direccion}
            onChange={handleChange}
            required
            placeholder="Ej: Av. Los Álamos 123, Distrito"
            maxLength={60}
          />
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="detallesCasa">Detalles de la casa:</label>
          <input
            type="text"
            id="detallesCasa"
            name="detallesCasa"
            value={formData.detallesCasa}
            onChange={handleChange}
            required
            placeholder="Ej: Casa azul de 2 pisos, reja negra"
            maxLength={100}
          />
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="tipoResiduo">Tipo de residuo:</label>
          <select
            id="tipoResiduo"
            name="tipoResiduo"
            value={formData.tipoResiduo}
            onChange={handleChange}
            required
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

        <div className={styles.formGroup}>
          <label htmlFor="tamañoResiduo">Tamaño de residuo:</label>
          <select
            id="tamañoResiduo"
            name="tamañoResiduo"
            value={formData.tamañoResiduo}
            onChange={handleChange}
            required
          >
            <option value="pequeño">Pequeño (1-5 kg) - Bolsa de basura regular</option>
            <option value="mediano">Mediano (5-20 kg) - Varias bolsas</option>
            <option value="grande">Grande (+20 kg) - Necesita transporte especial</option>
          </select>
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="referencia">Número de referencia:</label>
          <input
            type="text"
            id="referencia"
            name="referencia"
            value={formData.referencia}
            onChange={handleChange}
            required
            placeholder="Ej: 70707070"
            maxLength={8}
          />
        </div>

        <button type="submit" className={styles.submitButton}>
          Enviar
        </button>

        <p className={styles.helperText}>
          Rellena todos los espacios<br />
          para enviar tu solicitud
        </p>
      </form>
    </div>
  );
};

export default RecojoForm;