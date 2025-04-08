import React from 'react';
import { Link } from 'react-router-dom';
import { FaUsers, FaBuilding, FaHandsHelping, FaUserFriends } from 'react-icons/fa';
import styles from './Home.module.css';

const Home = () => {
  return (
    <>
      {/* Hero Section */}
      <section className={styles.heroSection}>
        <div className={styles.heroContent}>
          <div className={styles.heroText}>
            <h1>Transforma tu ciudad</h1>
            <p>¡Conviértete en un EcoHéroe!</p>
            <button className={styles.actionButton}>
              Iniciar Sesión
            </button>
          </div>
          <div className={styles.heroImage}>
            <img src="/Frame 2.svg" alt="EcoHeroes" className={styles.logoImage} />
          </div>
        </div>
      </section>

      {/* EcoHéroe Section */}
      <section className={styles.ecoHeroeSection}>
        <h2>EcoHéroe</h2>
        <p>
          La tecnología móvil puede transformar la manera en la
          que gestionamos los residuos, facilitando la
          comunicación entre ciudadanos y recolectores,
          optimización y promoviendo el reciclaje para un futuro
          más sostenible
        </p>
      </section>

      {/* Features Section */}
      <section className={styles.featuresSection}>
        <div className={styles.featureCard}>
          <div className={styles.featureIcon}>
            <FaUsers size={48} />
          </div>
          <h3>Solicita un recolector fácilmente</h3>
          <p>
            Agenda una recolección de materiales
            reciclables en tu hogar o negocio con
            solo unos clics. El sistema
            asignará el recolector más cercano para
            garantizar un servicio rápido y eficiente.
          </p>
        </div>

        <div className={styles.featureCard}>
          <div className={styles.featureIcon}>
            <FaBuilding size={48} />
          </div>
          <h3>Contribuye al medio ambiente</h3>
          <p>
            Registra los materiales reciclables
            que deseas entregar y obtén
            información sobre su impacto
            ambiental. Ayuda a reducir la
            contaminación mientras fomentas
            una cultura de reciclaje en tu
            comunidad.
          </p>
        </div>

        <div className={styles.featureCard}>
          <div className={styles.featureIcon}>
            <FaHandsHelping size={48} />
          </div>
          <h3>Monitorea y recibe recompensas</h3>
          <p>
            Lleva un control de tus
            solicitudes de recolección
            y obtén incentivos por tu
            compromiso con el
            reciclaje. Cuanto más
            recicles, más beneficios
            podrás recibir.
          </p>
        </div>
      </section>

      {/* Stats Section */}
      <section className={styles.statsSection}>
        <div className={styles.statsContent}>
          <div className={styles.statsLeft}>
            <h2>Cada día ayudando mas al planeta</h2>
            <p>Muchas personas se comprometen cada día a ayudar al planeta, tú también puedes.</p>
          </div>
          <div className={styles.statsRight}>
            <div className={styles.statItem}>
              <FaUserFriends className={styles.statIcon} />
              <div className={styles.statInfo}>
                <span className={styles.statNumber}>2,245,341</span>
                <span className={styles.statLabel}>Miembros</span>
              </div>
            </div>
            <div className={styles.statItem}>
              <FaHandsHelping className={styles.statIcon} />
              <div className={styles.statInfo}>
                <span className={styles.statNumber}>828,867</span>
                <span className={styles.statLabel}>Solicitudes completadas</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
};

export default Home; 