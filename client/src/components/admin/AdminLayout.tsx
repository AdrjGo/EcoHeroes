import React from 'react';
import { Outlet } from 'react-router-dom';
import Sidebar from './Sidebar';

const AdminLayout = () => {
  return (
    <div className="min-h-screen bg-gray-100 flex flex-col md:flex-row">
      <Sidebar />
      
      {/* Contenido Principal */}
      <div className="flex-1 min-h-screen ml-0 md:ml-64">
        {/* Header */}
        <header className="bg-white shadow-sm">
          <div className="px-6 py-4">
            <h1 className="text-2xl font-semibold text-gray-800">Administrador</h1>
          </div>
        </header>

        {/* Área de contenido */}
        <main className="p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default AdminLayout; 