import vuetify, { transformAssetUrls } from "vite-plugin-vuetify";
import { es } from "vuetify/lib/locale";
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2024-04-03",
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      baseURL: process.env.API_URL || "https://api.example.com/",
    },
  },
  build: {
    transpile: ["vuetify"],
  },
  modules: [
    (_options, nuxt) => {
      nuxt.hooks.hook("vite:extendConfig", (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({ autoImport: true }));
      });
    },
    "@pinia/nuxt",
    "@nuxtjs/i18n",
  ],
  vite: {
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },
  i18n: {
    // Lista de locales disponibles
    locales: [
      { code: "eu", name: "Euskera", iso: "eu", file: "eu.json" },
      { code: "es", name: "Español", iso: "es-ES", file: "es.json" },
    ],
    // Idioma predeterminado
    defaultLocale: "es",
    // Directorio donde están los archivos de traducción
    langDir: "locales/",
    // Habilita el modo de carga diferida para optimizar el rendimiento
    lazy: true,
    // Configuración de las rutas según el idioma
    strategy: "prefix_except_default", // prefijo para las rutas en idiomas no predeterminados
    detectBrowserLanguage: {
      useCookie: true, // Habilita el uso de cookies para mantener el idioma seleccionado
      cookieKey: "i18n_redirected",
      alwaysRedirect: false, // Evita la redirección al idioma por defecto al cambiar de ruta
      fallbackLocale: "es",
    },
    // Configuración de SEO para mejorar la accesibilidad en distintos idiomas
    seo: true,
  },
});
