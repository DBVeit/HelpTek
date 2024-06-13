<template>
  <LoginForm />
</template>

<script>
// @ is an alias to /src
import LoginForm from "@/components/LoginForm.vue";

export default {
  name: "LoginView",
  components: {
    LoginForm,
  },
  data() {
    return {
      loginViewStyle: null,
    };
  },
  mounted() {
    this.loadLoginViewStyle();
  },
  beforeDestroy() {
    this.unloadLoginViewStyle();
  },
  methods: {
    loadLoginViewStyle() {
      import("../assets/css/view/LoginView.css")
        .then(() => {
          console.log("LoginView style loaded");
        })
        .catch((err) => {
          console.error("LoginView style load failed", err);
        });
    },
    unloadLoginViewStyle() {
      this.loginViewStyle = null;
    },
  },
  /*created() {
    import("../assets/css/view/LoginView.css")
      .then(() => {
        console.log("LoginView style loaded");
      })
      .catch((err) => {
        console.error("LoginView style load failed", err);
      });
  },*/
};
</script>
