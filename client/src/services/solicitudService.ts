import axios, { AxiosError } from 'axios';

const API_URL = 'https://backendecoherores-production.up.railway.app/api'; // URL del servidor Laravel en Docker

export interface SolicitudData {
  nombre: string;
  direccion_recojo: string;
  detalles_casa: string;
  tipo_residuo: string;
  tamano_residuo: string;
  numero_referencia: string;
  carnet: string;
  latitud?: number;
  longitud?: number;
  fecha_solicitud: string;
}

export interface ErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
}

export const solicitudService = {
  async crearSolicitud(data: SolicitudData) {
    try {
      const response = await axios.post(`${API_URL}/solicitudes`, data, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      return response.data;
    } catch (error) {
      const axiosError = error as AxiosError<ErrorResponse>;
      if (axiosError.response?.status === 422) {
        const errorMessage = Object.entries(axiosError.response.data.errors || {})
          .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
          .join('\n');
        throw new Error(errorMessage);
      }
      throw error;
    }
  },

  async obtenerSolicitudes() {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.get(`${API_URL}/solicitudes`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(token && { 'Authorization': `Bearer ${token}` })
        }
      });
      return response.data;
    } catch (error) {
      console.error('Error al obtener las solicitudes:', error);
      throw error;
    }
  }
}; 