import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "bootstrap";
//import VueI18n from "vue-i18n"; // Importa o vue-i18n

import $ from "jquery";
window.jquery = $;
window.$ = $;
Vue.config.productionTip = false;

// Configura o vue-i18n
//Vue.use(VueI18n);

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
