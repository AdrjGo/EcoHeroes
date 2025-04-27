import React, { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';

const Sidebar = () => {
  const location = useLocation();
  const [isOpen, setIsOpen] = useState(false);
  const sidebarRef = useRef<HTMLDivElement | null>(null);

  const toggleSidebar = () => {
    setIsOpen(!isOpen);
  };

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (sidebarRef.current && !sidebarRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  const menuItems = [
    { path: '/admin/perfiles', text: 'Gestionar Perfiles', icon: '👥' },
    { path: '/admin/rutas', text: 'Rutas', icon: '🗺️' },
    { path: '/admin/solicitudes', text: 'Solicitudes', icon: '📝' },
  ];

  const isActive = (path: string) => location.pathname === path;

  return (
    <div ref={sidebarRef}>
      {/* Botón Hamburguesa */}
      <button className="md:hidden p-1.5 text-white bg-gray-800 transition-transform duration-300 fixed top-7 right-5 z-50 flex items-center justify-center rounded-full" onClick={toggleSidebar}>
        ☰
      </button>
      <div className={`h-screen w-64 bg-[#2D3741] flex flex-col fixed left-0 transform ${isOpen ? 'translate-x-0' : '-translate-x-full'} transition-transform duration-300 md:translate-x-0 z-50`}>
        {/* Logo y título */}
        <div className="p-4">
          <Link to="/admin" className="flex items-center space-x-2 no-underline">
            <img src="/logo-imagen.svg" alt="EcoHeroes" className="w-8 h-8" />
            <span className="text-xl font-bold text-white">EcoHeroes</span>
          </Link>
        </div>

        {/* Menú */}
        <nav className="flex-1 pt-4">
          <ul className="[&_a]:text-white [&_a]:no-underline">
            {menuItems.map((item) => (
              <li key={item.path}>
                <Link
                  to={item.path}
                  className={`flex items-center px-6 py-4 text-[15px] hover:bg-[#384451] transition-colors duration-200 text-white ${
                    isActive(item.path) ? 'bg-[#384451]' : ''
                  }`}
                >
                  <span className="mr-3 text-lg">{item.icon}</span>
                  <span className="text-white">{item.text}</span>
                </Link>
              </li>
            ))}
          </ul>
        </nav>

        {/* Botón Cerrar Sesión */}
        <div className="mt-auto">
          <Link
            to="/login"
            className="flex items-center px-6 py-4 text-[15px] hover:bg-[#384451] transition-colors duration-200 text-white no-underline"
          >
            <span className="mr-3">🚪</span>
            <span className="text-white">Cerrar sesión</span>
          </Link>
        </div>
      </div>
    </div>
  );
};

export default Sidebar; 