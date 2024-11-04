<template>
  <div>
    <div class="header">
      <img
        src="../assets/img/LogoHelpTek.png"
        alt="HelpTek Logo"
        class="logo"
      />
      <span> <i class="bi bi-building"></i> {{ nome_corporacao }}</span>
    </div>
    <div class="main-container">
      <aside class="sidebar">
        <div class="welcome-message">
          Bem vindo, <strong>{{ nameUser }}</strong> ({{ typeUser }})
          <br />
          <a href="#" @click.prevent="logout">Logout</a>
        </div>
        <nav class="nav-menu" v-if="!trocaSenhaLogin">
          <div v-for="(menuItem, index) in menuItems" :key="index">
            <a
              href="#"
              class="nav-item"
              @click.prevent="handleMenuClick(menuItem)"
              >{{ menuItem }}</a
            >
          </div>
        </nav>
      </aside>
      <main class="main-content">
        <CriarChamado v-if="CriarChamadoForm" />
        <MeusChamados v-if="MeusChamadosList && !isTecnico" />
        <MeusChamadosTec v-if="MeusChamadosList && isTecnico" />
        <TodosOsChamados v-if="TodosOsChamadosList" />
        <ConfiguracoesUsuarios v-if="ConfigUsuarios" />
        <DashboardChamados v-if="Dashboard" />
        <ConfiguracoesCorp v-if="ConfigCorp" />
        <ConfiguracoesSetores v-if="ConfigSetores" />
        <RelatorioChamados v-if="Relatorios" />
        <AtualizacoesHome v-if="Atualizacoes" />
        <PerfilUsers v-if="Perfil" />
        <TrocaSenhaView v-if="trocaSenhaLogin" />
      </main>
    </div>
  </div>
</template>
<script>
import CriarChamado from "@/components/CriarChamado.vue";
import MeusChamados from "@/components/MeusChamados.vue";
import MeusChamadosTec from "@/components/MeusChamadosTec.vue";
import TodosOsChamados from "@/components/TodosOsChamados.vue";
import ConfiguracoesUsuarios from "@/components/ConfiguracoesUsuarios.vue";
import axios from "axios";
import DashboardChamados from "@/components/DashboardChamados.vue";
import ConfiguracoesCorp from "@/components/ConfiguracoesCorp.vue";
import ConfiguracoesSetores from "@/components/ConfiguracoesSetores.vue";
import RelatorioChamados from "@/components/RelatorioChamados.vue";
import AtualizacoesHome from "@/components/AtualizacoesHome.vue";
import PerfilUsers from "@/components/PerfilUsers.vue";
import TrocaSenhaView from "@/views/TrocaSenhaView.vue";
export default {
  name: "HomePage",
  components: {
    TrocaSenhaView,
    DashboardChamados,
    CriarChamado,
    MeusChamados,
    MeusChamadosTec,
    TodosOsChamados,
    ConfiguracoesUsuarios,
    ConfiguracoesCorp,
    ConfiguracoesSetores,
    RelatorioChamados,
    AtualizacoesHome,
    PerfilUsers,
  },

  data() {
    const id_user = sessionStorage.getItem("id_user");
    return {
      menuItems: [],
      id_user: id_user,
      nameUser: "",
      typeUser: "",
      nome_corporacao: "",
      CriarChamadoForm: false,
      MeusChamadosList: false,
      TodosOsChamadosList: false,
      ConfigUsuarios: false,
      Dashboard: false,
      ConfigCorp: false,
      ConfigSetores: false,
      Relatorios: false,
      Atualizacoes: false,
      Perfil: false,
      isTecnico: false,
      showErrors: false,
      showMessage: false,
      trocaSenhaLogin: false,
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
    this.checkTrocaSenha();
    this.verificarTipoUsuario();
    this.fetchUserMenus();
    this.fetchUserCorp();
  },
  methods: {
    fetchUserMenus() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/getMenus.php?id_user=${this.id_user}`
        )
        .then((response) => {
          if (!response.data.error) {
            this.menuItems = response.data.menus;
          } else {
            console.error("Erro ao buscar menus: ", response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar menus: ", error);
        });
    },
    fetchUserCorp() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/fetch/fetchCorp.php?action=getUserCorp&id_user=${this.id_user}`
        )
        .then((res) => {
          this.nome_corporacao = res.data.corporacao[0].nome_corporacao;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    checkTrocaSenha() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/session/checkTrocaSenha.php?action=checkTrocaSenha&id_user=${this.id_user}`
        )
        .then((res) => {
          if (res.data.code === 428) {
            this.trocaSenhaLogin = true; // Bloqueia a navegação e ativa o formulário de troca de senha
          } else {
            // Se a troca de senha não é necessária, pode prosseguir normalmente
            this.trocaSenhaLogin = false;
            this.Atualizacoes = true;
          }
        });
    },
    handleMenuClick(menuItem) {
      if (menuItem === "Criar chamado") {
        this.CriarChamadoForm = true;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Meus chamados") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = true;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Todos os chamados") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = true;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Configurações de usuários") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = true;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Dashboard") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = true;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Configurações corporação") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = true;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Configurações setores") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = true;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Relatórios") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = true;
        this.Atualizacoes = false;
        this.Perfil = false;
      } else if (menuItem === "Atualizações") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = true;
        this.Perfil = false;
      } else if (menuItem === "Perfil") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
        this.Relatorios = false;
        this.Atualizacoes = false;
        this.Perfil = true;
      }
    },
    verificarTipoUsuario() {
      // Lógica para verificar se o usuário logado é um técnico
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/verificarTipoUsuario.php?id_user=${this.id_user}`
        )
        .then((res) => {
          this.isTecnico = res.data.isTecnico;
        })
        .catch((error) => {
          console.error("Erro ao verificar tipo de usuário: ", error);
        });
    },
    logout() {
      let id_user = this.id_user;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/logout.php?action=logout&id_user=${id_user}`
        )
        .then((res) => {
          if (res.data.error) {
            alert(res.data.msg);
          } else {
            sessionStorage.removeItem("token_session");
            sessionStorage.removeItem("id_user");
            sessionStorage.removeItem("first_name");
            sessionStorage.removeItem("level_user");
            this.$router.push("/Login");
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },
  },
};
</script>
<style>
@import "../assets/css/component/ConfiguracoesUsuarios.css";
</style>
