# Etapa de desarrollo para React

FROM node:22-alpine3.20 AS development
# Establecer directorio de trabajo dentro del contenedor
WORKDIR /usr/src/app

# Copiar solo el package.json y package-lock.json primero para aprovechar caché de Docker
COPY package.json package-lock.json ./

# Instalar dependencias usando npm ci para evitar cambios en el package-lock
RUN npm ci

# Copiar el resto del código al contenedor
COPY . .

# Definir variables de entorno si es necesario
ENV NODE_ENV=development
ENV VITE_API_URL=http://localhost:8000/api

# Exponer el puerto que usará el servidor de desarrollo
EXPOSE 5173

# Comando por defecto para correr la app en modo desarrollo, permitiendo acceso desde fuera del contenedor
CMD ["npm", "run", "dev", "--", "--host"]

