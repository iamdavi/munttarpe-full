// import this after install `@mdi/font` package
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { createVuetify } from "vuetify";
import { VTimePicker } from "vuetify/labs/VTimePicker";
import { aliases, mdi } from "vuetify/iconsets/mdi";
import { es } from "vuetify/locale"; // Importa el idioma español

export default defineNuxtPlugin((app) => {
  const vuetify = createVuetify({
    ssr: true,
    components: {
      ...components,
      VTimePicker,
    },
    directives,
    theme: {
      defaultTheme: "dark",
    },
    icons: {
      defaultSet: "mdi",
      aliases,
      sets: {
        mdi,
      },
    },
    locale: {
      locale: "es", // Establece el idioma por defecto a español
      messages: { es }, // Añade el idioma español a la configuración
    },
  });
  app.vueApp.use(vuetify);
});
