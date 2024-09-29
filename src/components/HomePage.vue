<template>
  <div>
    <div class="header">
      <img
        src="../assets/img/LogoHelpTek.png"
        alt="HelpTek Logo"
        class="logo"
      />
      <input type="search" placeholder="Pesquisar aqui" class="search-bar" />
      <span> <i class="bi bi-building"></i> CORP </span>
    </div>
    <div class="main-container">
      <aside class="sidebar">
        <div class="welcome-message">
          Bem vindo, <strong>{{ nameUser }}</strong> ({{ typeUser }})
          <br />
          <a href="#" @click.prevent="logout">Logout</a>
        </div>
        <nav class="nav-menu">
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
//import { Modal } from "bootstrap";
export default {
  name: "HomePage",
  components: {
    DashboardChamados,
    CriarChamado,
    MeusChamados,
    MeusChamadosTec,
    TodosOsChamados,
    ConfiguracoesUsuarios,
    ConfiguracoesCorp,
    ConfiguracoesSetores,
  },

  data() {
    const id_user = sessionStorage.getItem("id_user");
    return {
      menuItems: [],
      id_user: id_user,
      nameUser: "",
      typeUser: "",
      CriarChamadoForm: false,
      MeusChamadosList: false,
      TodosOsChamadosList: false,
      ConfigUsuarios: false,
      Dashboard: false,
      ConfigCorp: false,
      ConfigSetores: false,
      isTecnico: false,
      showErrors: false,
      showMessage: false,
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
    //this.checkTrocaSenha();
    this.verificarTipoUsuario();
    this.fetchUserMenus();
  },
  /*mounted() {
    this.modalRedefinirSenha = new Modal(
      document.getElementById("modalRedefinirSenha")
    );
  },*/
  methods: {
    /*checkTrocaSenha() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/session/checkTrocaSenha.php?action=checkTrocaSenha&id_user=${this.id_user}`
        )
        .then((res) => {
          if (res.data.code === 200) {
            this.$router.push("/Home");
            localStorage.setItem("token", res.data.token);
            sessionStorage.setItem("id_user", res.data.id_user);
            sessionStorage.setItem("first_name", res.data.first_name);
            sessionStorage.setItem("level_user", res.data.level_user);
            sessionStorage.setItem("permission", res.data.user_permission);
          } else if (res.data.code === 409) {
            this.openActiveSessionModal(); // Abre o modal ao detectar a sessão ativa
            this.sessionToken = res.data.token;
          } else {
            this.errorMessage = res.data.msg;
            this.fadeOutErrorMessage();
          }
        });
    },*/

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
    handleMenuClick(menuItem) {
      if (menuItem === "Criar chamado") {
        this.CriarChamadoForm = true;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
      } else if (menuItem === "Meus chamados") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = true;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
      } else if (menuItem === "Todos os chamados") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = true;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
      } else if (menuItem === "Configurações de usuários") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = true;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
      } else if (menuItem === "Dashboard") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = true;
        this.ConfigCorp = false;
        this.ConfigSetores = false;
      } else if (menuItem === "Configurações corporação") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = true;
        this.ConfigSetores = false;
      } else if (menuItem === "Configurações setores") {
        this.CriarChamadoForm = false;
        this.MeusChamadosList = false;
        this.TodosOsChamadosList = false;
        this.ConfigUsuarios = false;
        this.Dashboard = false;
        this.ConfigCorp = false;
        this.ConfigSetores = true;
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
    /*openTrocaSenhaModal() {
      if (this.modalRedefinirSenha) {
        this.modalRedefinirSenha.show();
      } else {
        console.error("Modal não foi inicializado corretamente.");
      }
    },
    onPasswordChange() {
      if (!this.newPassword || !this.confirmPassword) {
        alert("Por favor, preencha todos os campos.");
        return;
      }
      if (this.newPassword !== this.confirmPassword) {
        alert("As senhas não coincidem.");
        return;
      }

      let data = new FormData();
      data.append("id_user", this.userId);
      data.append("new_password", this.newPassword);
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/changePassword.php",
          data
        )
        .then((res) => {
          if (res.data.error) {
            alert(res.data.msg);
          } else {
            this.showPasswordChangeForm = false;
            this.$router.push("/Home");
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },*/
    // Validação do padrão de senha
    /*validatePassword() {
      const password = this.NovaSenha.password_user;
      const passwordRegex =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

      if (!password) {
        this.passwordValidationMessage = "";
        this.validaSenha = false;
      } else if (!passwordRegex.test(password)) {
        this.passwordValidationMessage = "bi bi-x text-danger";
        this.validaSenha = false;
      } else {
        this.passwordValidationMessage = "bi bi-check2 text-success";
        this.validaSenha = true;
      }
    },*/
    // Validação de senhas inseridas / comparação
    /*validatePasswordMatch() {
      if (this.NovaSenha.password_user !== this.NovaSenha.confirma_senha) {
        this.passwordMatchMessage = "As senhas não coincidem.";
        this.matchSenha = false;
      } else {
        this.passwordMatchMessage = "";
        this.matchSenha = true;
      }
    },*/
    /*fadeOutErrorMessage() {
      let opacity = 1;
      const interval = setInterval(() => {
        opacity -= 0.1;
        this.errorMessageOpacity = opacity;
        if (opacity <= 0) {
          clearInterval(interval);
          this.errorMessage = "";
          this.errorMessageOpacity = 1;
        }
      }, 250);
    },*/
  },
};
</script>
<style>
@import "../assets/css/component/ConfiguracoesUsuarios.css";
</style>
