import React, { useState } from 'react';
import { FaEnvelope, FaMapMarkerAlt, FaPhone } from 'react-icons/fa';
import { TbBackground } from 'react-icons/tb';

const Contacto: React.FC = () => {
  const [formData, setFormData] = useState({
    nombre: '',
    email: '',
    mensaje: ''
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    console.log('Formulario enviado:', formData);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;

    if (name === 'nombre') {
      const letterValue = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
      if (letterValue.length <= 60) {
        setFormData({
          ...formData,
          [name]: letterValue
        });
      }
    } else if (name === 'email') {
      setFormData({
        ...formData,
        [name]: value
      });
    } else if (name === 'mensaje') {
      if (value.length <= 500) {
        setFormData({
          ...formData,
          [name]: value
        });
      }
    } else {
      setFormData({
        ...formData,
        [name]: value
      });
    }
  };

  return (
    <div className="py-16 px-8 max-w-6xl mx-auto min-h-[calc(100vh-64px)] bg-gray-50">
      <h1 className="text-4xl text-green-700 mb-4 text-center font-semibold">
        Contáctanos
      </h1>
      <p className="text-center text-gray-600 text-lg mb-12 max-w-2xl mx-auto">
        Estamos aquí para ayudarte. Envíanos tus preguntas o comentarios.
      </p>
      
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-12 bg-white p-12 rounded-xl shadow-md">
        <div className="space-y-8">
          <div className="p-6 bg-gray-50 rounded-lg text-center transition-transform hover:-translate-y-1">
            <FaPhone className="text-3xl text-green-700 mb-4 mx-auto" />
            <h3 className="text-xl text-gray-800 mb-2">Teléfono</h3>
            <p className="text-gray-600">+51 123 456 789</p>
          </div>
          <div className="p-6 bg-gray-50 rounded-lg text-center transition-transform hover:-translate-y-1">
            <FaEnvelope className="text-3xl text-green-700 mb-4 mx-auto" />
            <h3 className="text-xl text-gray-800 mb-2">Email</h3>
            <p className="text-gray-600">contacto@ecoheroes.com</p>
          </div>
          <div className="p-6 bg-gray-50 rounded-lg text-center transition-transform hover:-translate-y-1">
            <FaMapMarkerAlt className="text-3xl text-green-700 mb-4 mx-auto" />
            <h3 className="text-xl text-gray-800 mb-2">Dirección</h3>
            <p className="text-gray-600">Av. Principal 123, Lima</p>
          </div>
        </div>

        <div className="lg:col-span-2">
          <h2 className="text-2xl text-gray-800 mb-8 font-medium">
            Envíanos un mensaje
          </h2>
          <form className="space-y-6" onSubmit={handleSubmit}>
            <div className="space-y-2">
              <label htmlFor="nombre" className="font-medium text-gray-800 text-sm">
                Nombre completo
              </label>
              <input
                type="text"
                id="nombre"
                name="nombre"
                value={formData.nombre}
                onChange={handleChange}
                placeholder="Ingresa tu nombre"
                required
                maxLength={60}
                className="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-black focus:border-green-700 focus:ring-2 focus:ring-green-100 transition-colors"
              />
            </div>
            <div className="space-y-2">
              <label htmlFor="email" className="font-medium text-gray-800 text-sm">
                Correo electrónico
              </label>
              <input
                type="email"
                id="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                placeholder="ejemplo@correo.com"
                required
                className="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-black focus:border-green-700 focus:ring-2 focus:ring-green-100 transition-colors"
              />
            </div>
            <div className="space-y-2">
              <label htmlFor="mensaje" className="font-medium text-gray-800 text-sm">
                Mensaje
              </label>
              <textarea
                id="mensaje"
                name="mensaje"
                value={formData.mensaje}
                onChange={handleChange}
                placeholder="¿En qué podemos ayudarte?"
                required
                rows={6}
                maxLength={500}
                className="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-black focus:border-green-700 focus:ring-2 focus:ring-green-100 transition-colors min-h-[150px]"
              ></textarea>
            </div>
            <button
              type="submit"
              style={{ backgroundColor: '#38a169' }}
              className="w-full py-4 px-6 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 hover:shadow-md active:translate-y-0 transition-all"
            >
              Enviar Mensaje
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Contacto;