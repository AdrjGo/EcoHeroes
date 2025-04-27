import { createBrowserRouter } from 'react-router-dom';
import App from '../App';
import SolicitudPage from '../pages/Solicitud';
import Inicio from '../pages/Inicio';
import Contacto from '../pages/Contacto';
import EcoHeroes from '../pages/EcoHeroes';
import LoginPage from '../pages/Login';
import RegisterPage from '../pages/Register';
import AdminLayout from '../components/admin/AdminLayout';
import Perfiles from '../pages/admin/Perfiles';
import Rutas from '../pages/admin/Rutas';
import SolicitudesAdmin from '../pages/admin/SolicitudesAdmin';

export const router = createBrowserRouter([
  {
    path: '/',
    element: <App />,
    children: [
      {
        path: '/',
        element: <Inicio />,
      },
      {
        path: 'login',
        element: <LoginPage />,
      },
      {
        path: 'registro',
        element: <RegisterPage />,
      },
      {
        path: 'solicitar-recojo',
        element: <SolicitudPage />,
      },
      {
        path: 'ecoheroes',
        element: <EcoHeroes />,
      },
      {
        path: 'contacto',
        element: <Contacto />,
      },
    ],
  },
  {
    path: '/admin',
    element: <AdminLayout />,
    children: [
      {
        path: 'perfiles',
        element: <Perfiles />,
      },
      {
        path: 'rutas',
        element: <Rutas />,
      },
      {
        path: 'solicitudes',
        element: <SolicitudesAdmin />,
      },
    ],
  },
]);