import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "bootstrap";
//import "bootstrap/dist/css/bootstrap.min.css"; // Adicionando Bootstrap CSS
//import "bootstrap-vue/dist/bootstrap-vue.css"; // Adicionando BootstrapVue CSS

import $ from "jquery";
window.jquery = $;
window.$ = $;
Vue.config.productionTip = false;

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
