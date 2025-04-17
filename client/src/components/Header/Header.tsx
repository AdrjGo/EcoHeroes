import { Link } from 'react-router-dom';
import { useState, useEffect, useRef } from 'react';
import logo from '../../assets/logo-imagen.webp';

export default function Header() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const menuRef = useRef<HTMLDivElement>(null);

  const toggleMenu = () => {
    setIsMenuOpen((prev) => !prev);
  };

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (menuRef.current && !menuRef.current.contains(event.target as Node)) {
        setIsMenuOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <header className="bg-[#3d3e40] py-4 w-full sticky top-0 z-50 shadow-md">
      <div className="max-w-7xl mx-auto px-4 flex justify-between items-center">
        
        {/* Botón Hamburguesa */}
        <button className="bg-[#3d3e40] text-white text-2xl md:hidden" onClick={toggleMenu} aria-expanded={isMenuOpen} aria-label="Toggle menu">
          ☰
        </button>

        {/* Logo */}
        <Link to="/" className="flex items-center gap-4 text-white no-underline">
          <img src={logo} alt="EcoHeroes Logo" className="h-10 object-contain" />
          <span className="text-xl md:text-2xl font-bold !text-white">EcoHeroes</span>
        </Link>

        {/* Menú de navegación */}
        <nav ref={menuRef} className={`${isMenuOpen ? 'flex' : 'hidden'} md:flex ${isMenuOpen ? 'flex-col absolute top-full left-0 right-0 bg-[#3d3e40] p-4 shadow-md' : 'gap-8'} transition-all duration-300`}>
          {[
            { path: '/', label: 'Inicio' },
            { path: '/solicitar-recojo', label: 'Solicitar Recojo' },
            { path: '/ecoheroes', label: 'EcoHeroes' },
            { path: '/contacto', label: 'Contacto' },
          ].map(({ path, label }) => (
            <Link
              key={path}
              to={path}
              className="relative !text-white no-underline text-base font-medium hover:text-[#4CAF50] transition-colors duration-300 after:content-[''] after:absolute after:w-0 after:h-0.5 after:bottom-0 after:left-0 after:bg-[#4CAF50] hover:after:w-full after:transition-all after:duration-300"
              onClick={() => setIsMenuOpen(false)} // cerrar el menú al hacer clic
            >
              {label}
            </Link>
          ))}
        </nav>
      </div>
    </header>
  );
}
