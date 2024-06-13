<template>
  <div>
    <div class="header">
      <img
        src="../assets/img/LogoHelpTek.png"
        alt="HelpTek Logo"
        class="logo"
      />
      <input type="search" placeholder="Pesquisar aqui" class="search-bar" />
    </div>
    <div class="main-container">
      <aside class="sidebar">
        <div class="welcome-message">
          Bem vindo, <strong>{{ nameUser }}</strong> ({{ typeUser }})
          <br />
          <a href="#" @click.prevent="logout">Logout</a>
        </div>
        <nav class="nav-menu">
          <a href="#" class="nav-item">Atualizações</a>
          <a
            class="nav-item"
            @click.prevent="
              CriarChamadoForm = true;
              MeusChamadosList = false;
            "
            >Criar chamado</a
          >
          <a
            class="nav-item"
            @click.prevent="
              MeusChamadosList = true;
              CriarChamadoForm = false;
            "
            >Meus chamados</a
          >
          <a href="#" class="nav-item">Todos os chamados</a>
          <a href="#" class="nav-item">Dashboard</a>
          <a href="#" class="nav-item">Relatórios</a>
          <a href="#" class="nav-item">Perfil</a>
          <a href="#" class="nav-item">Configurações do sistema</a>
          <a href="#" class="nav-item">Configurações de usuários</a>
        </nav>
      </aside>
      <main class="main-content">
        <CriarChamado v-if="CriarChamadoForm" />
        <MeusChamados v-if="MeusChamadosList" />
        <!--<TodosChamados />
        <Atualizacoes />
        <Dashboard />
        <Relatorios />
        <Perfil />
        <ConfigSist />
        <ConfigUsers />-->
      </main>
    </div>
  </div>
</template>
<script>
import CriarChamado from "@/components/CriarChamado.vue";
import MeusChamados from "@/components/MeusChamados.vue";

export default {
  name: "HomePage",
  components: {
    CriarChamado,
    MeusChamados,
  },

  data() {
    const id_user = sessionStorage.getItem("id_user");
    return {
      id_user: id_user,
      nameUser: "",
      typeUser: "",
      CriarChamadoForm: false,
      MeusChamadosList: false,
    };
  },

  created() {
    import("../assets/css/component/HomePage.css")
      .then(() => {
        console.log("HomeView style loaded");
      })
      .catch((err) => {
        console.error("HomeView style load failed", err);
      });
    const nameUser = sessionStorage.getItem("first_name");
    const typeUser = sessionStorage.getItem("level_user");

    this.nameUser = nameUser || "Usuário";
    this.typeUser = typeUser || "Usuário";
  },

  methods: {
    logout() {
      localStorage.removeItem("token");
      sessionStorage.removeItem("id_user");
      sessionStorage.removeItem("first_name");
      sessionStorage.removeItem("level_user");

      this.$router.push("/Login");
    },
  },
};
</script>
