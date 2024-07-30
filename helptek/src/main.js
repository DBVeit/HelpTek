import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "bootstrap";

import $ from "jquery";
window.jquery = $;
window.$ = $;
Vue.config.productionTip = false;

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
