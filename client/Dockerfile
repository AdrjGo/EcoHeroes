# Etapa de desarrollo
FROM node:18-alpine AS development

WORKDIR /usr/src/app

# Instalar dependencias con cache
COPY package.json package-lock.json ./
RUN npm install --frozen-lockfile

# Copiar el resto
COPY . .

# Variables de entorno para desarrollo
ENV NODE_ENV=development
ENV VITE_API_URL=http://server:8000/api

EXPOSE 5173

CMD ["npm", "run", "dev"]

# ----------------------------------------
# Etapa de producción
FROM node:18-alpine AS builder

WORKDIR /usr/src/app
COPY . .
RUN npm install && npm run build

FROM nginx:alpine AS production
COPY --from=builder /usr/src/app/dist /usr/share/nginx/html
COPY --from=builder /usr/src/app/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
