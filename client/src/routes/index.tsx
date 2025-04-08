import { createBrowserRouter } from 'react-router-dom';
import App from '../App';
import Home from '../pages/Home/Home';
import Contacto from '../pages/Contacto';
import Solicitud from '../pages/Solicitud';
import EcoHeroes from '../pages/EcoHeroes/EcoHeroes';

export const router = createBrowserRouter([
  {
    path: '/',
    element: <App />,
    children: [
      {
        index: true,
        element: <Home />,
      },
      {
        path: 'contacto',
        element: <Contacto />,
      },
      {
        path: 'solicitar-recojo',
        element: <Solicitud />,
      },
      {
        path: 'ecoheroes',
        element: <EcoHeroes />,
      },
    ],
  },
]); 