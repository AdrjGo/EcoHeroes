import { Link } from 'react-router-dom';
import styles from './Header.module.css';
import logo from '../../assets/logo-imagen.webp';
import { useEffect, useRef, useState } from 'react';

export default function Header() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const menuRef = useRef<HTMLDivElement | null>(null);

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
    console.log('Menu open:', !isMenuOpen);
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
    <header className={styles.header}>
      <div className={styles.container} ref={menuRef}>
        <button className={styles.hamburger} onClick={toggleMenu}>
          ☰
        </button>
        <Link to="/" className={styles.logoContainer}>
          <img src={logo} alt="EcoHeroes Logo" className={styles.logoImage} />
          <span className={styles.logoText}>EcoHeroes</span>
        </Link>
        <nav className={`${styles.nav} ${isMenuOpen ? styles.navOpen : ''}`}>
          <Link to="/">Inicio</Link>
          <Link to="/solicitar-recojo">Solicitar Recojo</Link>
          <Link to="/ecoheroes">EcoHeroes</Link>
          <Link to="/contacto">Contacto</Link>
        </nav>
      </div>
    </header>
  );
}