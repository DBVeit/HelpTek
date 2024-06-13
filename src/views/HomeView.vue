<template>
  <HomePage />
</template>

<script>
// @ is an alias to /src
import HomePage from "@/components/HomePage.vue";

export default {
  name: "HomeView",
  components: {
    HomePage,
  },
  created() {
    const token = localStorage.getItem("token");
    const id_user = sessionStorage.getItem("id_user");
    if (!token || !id_user) {
      this.$router.push("/Login");
    } else {
      import("../assets/css/view/HomeView.css")
        .then(() => {
          console.log("HomeView style loaded");
        })
        .catch((err) => {
          console.error("HomeView style load failed", err);
        });
    }
  },
};
</script>
