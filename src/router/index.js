import Vue from "vue";
import VueRouter from "vue-router";
import LoginView from "@/views/LoginView.vue";
import HomeView from "@/views/HomeView.vue";
import AnalyticsView from "@/views/AnalyticsView.vue"; 

Vue.use(VueRouter);

const routes = [
  {
    path: "/Login",
    name: "login",
    component: LoginView,
  },
  {
    path: "/Home",
    name: "home",
    component: HomeView,
  },
  {
    path: "/Analytics",
    name: "analytics",
    component: AnalyticsView,
  },
  {
    path: "/",
    redirect: "/Login",
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
