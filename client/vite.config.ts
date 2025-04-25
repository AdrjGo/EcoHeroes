import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import path from "path"; // Importa el módulo 'path' de Node.js

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tailwindcss()],
  resolve: {
    alias: {
      // Asegura que las importaciones de 'sweetalert2' apunten al paquete instalado
      sweetalert2: path.resolve(__dirname, "node_modules/sweetalert2/dist/sweetalert2.js"),
    },
  },
});