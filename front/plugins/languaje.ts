// Locale
import { createI18n } from "vue-i18n";
import esLang from "../translations/es";
import euLang from "../translations/eu";

const messages = { es: esLang, eu: euLang };

export const i18n = createI18n({
  legacy: false, // Vuetify does not support the legacy mode of vue-i18n
  locale: "es",
  fallbackLocale: "es",
  messages,
});
