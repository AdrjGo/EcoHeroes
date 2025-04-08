# EcoHeroes - Sistema de Gestión de Residuos

## Descripción
EcoHeroes es una aplicación web para la gestión y recolección de residuos, desarrollada con React y TypeScript. Permite a los usuarios solicitar recolecciones de residuos y visualizar su ubicación en un mapa interactivo.

## Tecnologías Utilizadas
- React 18
- TypeScript
- Vite
- React Router DOM
- Google Maps API
- React Icons

## Configuración del Proyecto

### Requisitos Previos
- Node.js (versión 14 o superior)
- npm o yarn
- Cuenta de Google Cloud Platform (para la API de Maps)

### Instalación
1. Clonar el repositorio:
```bash
git clone [URL_DEL_REPOSITORIO]
cd EcoHeroes
```

2. Instalar dependencias:
```bash
cd client
npm install
```

3. Configurar variables de entorno:
Crear un archivo `.env` en la carpeta `client` con:
```
VITE_GOOGLE_MAPS_API_KEY=tu_api_key_aquí
```

4. Iniciar el servidor de desarrollo:
```bash
npm run dev
```

## Estructura del Proyecto
```
client/
├── src/
│   ├── components/
│   │   ├── Header/
│   │   ├── footer/
│   │   ├── RecojoForm/
│   │   └── Map/
│   ├── pages/
│   │   ├── Contacto/
│   │   └── Solicitud/
│   ├── types/
│   └── App.tsx
```

## Dependencias Instaladas

### Dependencias Principales
- `react-router-dom` - Para el enrutamiento de la aplicación
- `@types/react` - Para tipos de TypeScript
- `@types/react-dom` - Para tipos de TypeScript

### Paquetes de Iconos
- `react-icons` - Biblioteca de iconos
  - `react-icons/fa` - Iconos de Font Awesome
  - `react-icons/io5` - Iconos de Ionicons 5
  - `react-icons/md` - Iconos de Material Design

### Paquetes de Mapa
- `@react-google-maps/api` - Integración con Google Maps
- `@types/google.maps` - Tipos de TypeScript para Google Maps

### Componentes Personalizados
- `Header` - Barra de navegación superior
- `Footer` - Pie de página con información de contacto
- `RecojoForm` - Formulario de solicitud de recolección
- `Map` - Componente de mapa interactivo

## Cambios Recientes

### 1. Mejoras en la Página de Contacto
- Rediseño completo del formulario de contacto
- Implementación de un diseño de dos columnas
- Adición de iconos para información de contacto
- Mejoras en la responsividad y estilos

### 2. Formulario de Recojo
- Actualización del formulario de solicitud de recolección
- Adición del campo "Detalles de la casa"
- Mejora en los selectores de tipo y tamaño de residuo
- Implementación de validación de campos requeridos

### 3. Mapa
- Ajustes en el tamaño y posicionamiento del mapa
- Implementación de un diseño cuadrado (400x400px)
- Centrado vertical y horizontal
- Ajustes responsivos para diferentes tamaños de pantalla

### 4. Estilos y Diseño
- Aumento del border-radius en campos de formulario (12px)
- Mejoras en la consistencia visual
- Ajustes en el espaciado y márgenes
- Optimización de la experiencia móvil

## Próximos Pasos
- Implementar funcionalidad de envío de formularios
- Mejorar la validación de campos
- Optimizar el rendimiento del mapa
- Añadir animaciones y transiciones
- Implementar sistema de autenticación
- Añadir panel de administración

## Contribución
1. Fork del proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Licencia
Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles. 