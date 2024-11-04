import { createI18n } from "vue-i18n";

// Definindo as mensagens para cada idioma
const messages = {
  en: {
    welcome: "Welcome",
    login: "Login",
  },
  pt: {
    welcome: "Bem-vindo",
    login: "Entrar",
  },
};

const i18n = createI18n({
  locale: "pt", // Idioma padrão
  messages,
});

export default i18n;
