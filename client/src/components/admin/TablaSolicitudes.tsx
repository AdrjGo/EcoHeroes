import React, { useState, useEffect } from 'react';
import { solicitudService } from '../../services/solicitudService';

interface Solicitud {
  id: number;
  nombre: string;
  carnet: string;
  direccion_recojo: string;
  fecha_solicitud: string;
  tipo_residuo: string;
  tamano_residuo: string;
  numero_referencia: string;
  estado_solicitud: string;
}

const resultadosPorPagina = [5, 10, 25, 50, 100];

const TablaSolicitudes: React.FC = () => {
  const [pagina, setPagina] = useState(1);
  const [porPagina, setPorPagina] = useState(10);
  const [solicitudes, setSolicitudes] = useState<Solicitud[]>([]);
  const [total, setTotal] = useState(0);

  useEffect(() => {
    const fetchSolicitudes = async () => {
      try {
        const res = await solicitudService.obtenerSolicitudes();
        if (res.data && res.data.data) {
          setSolicitudes(res.data.data);
          setTotal(res.data.total || res.data.data.length);
        } else if (Array.isArray(res.data)) {
          setSolicitudes(res.data);
          setTotal(res.data.length);
        } else {
          setSolicitudes([]);
          setTotal(0);
        }
      } catch {
        setSolicitudes([]);
        setTotal(0);
      }
    };
    fetchSolicitudes();
  }, []);

  const totalPaginas = Math.ceil(total / porPagina);
  const solicitudesMostradas = solicitudes.slice((pagina - 1) * porPagina, pagina * porPagina);

  return (
    <div className="bg-white rounded-2xl shadow-xl p-6 w-full">
      <div className="flex items-center justify-between mb-4">
        <div className="flex items-center gap-2">
          <span className="text-sm text-gray-600">Ver</span>
          <select
            className="border rounded px-2 py-1 text-sm"
            value={porPagina}
            onChange={e => {
              setPorPagina(Number(e.target.value));
              setPagina(1);
            }}
          >
            {resultadosPorPagina.map(num => (
              <option key={num} value={num}>{num}</option>
            ))}
          </select>
          <span className="text-sm text-gray-600">resultados por página</span>
        </div>
      </div>
      <div className="overflow-x-auto">
        <table className="min-w-full text-sm">
          <thead>
            <tr className="bg-green-100 text-gray-700">
              <th className="px-3 py-2 text-left">Nombre</th>
              <th className="px-3 py-2 text-left">Carnet</th>
              <th className="px-3 py-2 text-left">Dirección</th>
              <th className="px-3 py-2 text-left">Fecha solicitud</th>
              <th className="px-3 py-2 text-left">Tipo residuo</th>
              <th className="px-3 py-2 text-left">Tamaño</th>
              <th className="px-3 py-2 text-left">Referencia</th>
              <th className="px-3 py-2 text-left">Estado</th>
            </tr>
          </thead>
          <tbody>
            {solicitudesMostradas.map((solicitud) => (
              <tr key={solicitud.id} className="border-b last:border-b-0 hover:bg-green-50 bg-white">
                <td className="px-3 py-2 text-gray-900">{solicitud.nombre}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.carnet}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.direccion_recojo}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.fecha_solicitud?.slice(0,10)}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.tipo_residuo}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.tamano_residuo}</td>
                <td className="px-3 py-2 text-gray-900">{solicitud.numero_referencia}</td>
                <td className="px-3 py-2">
                  {solicitud.estado_solicitud === 'aprobada' || solicitud.estado_solicitud === 'completada' ? (
                    <span className="bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold capitalize">{solicitud.estado_solicitud}</span>
                  ) : (
                    <span className="bg-yellow-400 text-white px-2 py-1 rounded text-xs font-semibold capitalize">{solicitud.estado_solicitud}</span>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <div className="flex items-center justify-between mt-4 text-sm text-gray-600">
        <span>
          Página {pagina} de {totalPaginas} ({total} resultados)
        </span>
        <div className="flex items-center gap-2">
          <button
            className="px-2 py-1 rounded border bg-gray-100 disabled:opacity-50"
            onClick={() => setPagina(pagina - 1)}
            disabled={pagina === 1}
          >Anterior</button>
          <span>{pagina}</span>
          <button
            className="px-2 py-1 rounded border bg-gray-100 disabled:opacity-50"
            onClick={() => setPagina(pagina + 1)}
            disabled={pagina === totalPaginas}
          >Siguiente</button>
        </div>
      </div>
    </div>
  );
};

export default TablaSolicitudes; 