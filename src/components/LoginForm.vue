<template>
  <div class="main-div">
    <header class="login-header">
      <p>Inovação em serviços HelpDesk</p>
    </header>
    <div class="content-wrapper">
      <div class="container login-box" v-if="!showActiveSessionMessage">
        <div class="login_logo">
          <img src="../assets/img/LogoHelpTek.png" alt="Logo HelpTek" />
        </div>
        <div
          class="error-message"
          v-if="errorMessage"
          :style="{ opacity: errorMessageOpacity }"
        >
          {{ errorMessage }}
        </div>
        <!----------------------------------Modal p/ derrubar sessão ativa---------------------------------->
        <div
          class="modal fade"
          id="activeSessionModal"
          tabindex="-1"
          aria-labelledby="activeSessionModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="activeSessionModalLabel">
                  Sessão Ativa
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <p>
                  Uma sessão ativa foi detectada. Deseja derrubar a sessão
                  anterior?
                </p>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Não
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="confirmActiveSessionOverride"
                >
                  Sim
                </button>
              </div>
            </div>
          </div>
        </div>
        <!----------------------------------Modal p/ derrubar sessão ativa---------------------------------->
        <!----------------------------------Formulário de login---------------------------------->
        <div id="loginform" v-if="loginform">
          <form method="POST" class="login-form" @submit.prevent="onLogin()">
            <div class="input-group">
              <input
                type="text"
                id="username"
                v-model="User.username"
                placeholder="Nome de usuário"
                name="username"
                autocomplete="off"
                required
              />
            </div>
            <div class="input-group">
              <input
                type="password"
                id="password"
                v-model="User.password"
                placeholder="Senha"
                name="password"
                autocomplete="off"
                required
              />
            </div>
            <div class="forgot-recover">
              <a
                class="recover-password"
                @click.prevent="
                  recoverform = true;
                  loginform = false;
                "
                >Recuperar Login</a
              >
            </div>
            <button type="submit" class="button-submit-login">Entrar</button>
          </form>
        </div>
        <!----------------------------------Formulário de login---------------------------------->
        <!----------------------------------Formulário recuperação de senha---------------------------------->
        <div id="recoverform" v-if="recoverform">
          <form method="POST" class="login-form" @submit.prevent="onRecovery()">
            <div class="input-group">
              <input
                type="email"
                id="email_user"
                v-model="Rec.emailUser"
                placeholder="E-Mail"
                name="email"
                autocomplete="off"
                required
              />
            </div>
            <div class="forgot-recover">
              <label>
                Preencha o e-mail para recuperar o acesso ou clique
                <a
                  class="recover-password"
                  @click.prevent="
                    recoverform = false;
                    loginform = true;
                  "
                  >aqui</a
                >
                retornar
              </label>
            </div>
            <button type="submit" class="button-submit-login">Enviar</button>
          </form>
        </div>
        <!----------------------------------Formulário recuperação de senha---------------------------------->
      </div>
    </div>
    <!----------------------------------Rodapé---------------------------------->
    <footer class="login-footer">
      <div class="footer-basic">
        <footer>
          <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Serviços</a></li>
            <li class="list-inline-item"><a href="#">Sobre</a></li>
            <li class="list-inline-item"><a href="#">Termos</a></li>
            <li class="list-inline-item">
              <a href="#">Políticas de Privacidade</a>
            </li>
          </ul>
          <p class="copyright">HelpTek © 2024</p>
        </footer>
      </div>
    </footer>
    <!----------------------------------Rodapé---------------------------------->
  </div>
</template>
<script>
import axios from "axios";
import CryptoJS from "crypto-js";
import { Modal } from "bootstrap"; // Importa o Modal do Bootstrap

export default {
  name: "LoginForm",
  data() {
    return {
      loginform: true,
      recoverform: false,
      User: { username: "", password: "" },
      Rec: { emailUser: "" },
      errorMessage: "",
      errorMessageOpacity: 1,
      recoverMessage: "",
      recoverMessageOpacity: 1,
      showMessage: false,
      message: "",
      userId: null,
      showActiveSessionMessage: false,
      sessionToken: null,
      activeSessionModal: null,
    };
  },
  mounted() {
    // Inicializa o modal após o DOM ser carregado
    this.activeSessionModal = new Modal(
      document.getElementById("activeSessionModal")
    );
  },
  methods: {
    //*********Função p/ realizar login*********//
    onLogin() {
      if (!this.User.username || !this.User.password) {
        this.showAlert = "Por favor, preencha todos os campos.";
        return;
      }

      let data = new FormData();

      // eslint-disable-next-line no-undef
      const encryptedPassword = CryptoJS.SHA256(this.User.password).toString();

      data.append("username", this.User.username);
      data.append("password", encryptedPassword);
      // Cria um objeto para armazenar os dados
      /*let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries);*/ // Exibe o objeto com os dados
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/login.php?action=login",
          data
        )
        .then((res) => {
          if (res.data.code === 200) {
            localStorage.setItem("token", res.data.token);
            sessionStorage.setItem("id_user", res.data.id_user);
            sessionStorage.setItem("first_name", res.data.first_name);
            sessionStorage.setItem("level_user", res.data.level_user);
            sessionStorage.setItem("permission", res.data.user_permission);
            this.$router.push("/Home");
          } else if (res.data.code === 409) {
            this.openActiveSessionModal(); // Abre o modal ao detectar a sessão ativa
            this.sessionToken = res.data.token;
          } else {
            this.errorMessage = res.data.msg;
            this.fadeOutErrorMessage();
          }
        })
        .catch((err) => {
          alert(
            "Internal Error (Status code: 500) - Servidor encontra-se inativo ou desabilitado!"
          );
          console.log("Err", err);
        });
    },
    //*********Abrir modal de sessão ativa*********//
    openActiveSessionModal() {
      if (this.activeSessionModal) {
        this.activeSessionModal.show();
      } else {
        console.error("Modal não foi inicializado corretamente.");
      }
    },
    //*********Função p/ recuperar login*********//
    onRecovery() {
      if (!this.Rec.emailUser) {
        alert("Por favor, preencha o campo.");
        return;
      }

      // Gerar uma senha aleatória
      const randomPassword = this.generateRandomPassword();

      // Criptografar a senha gerada
      const encryptedPassword = CryptoJS.SHA256(randomPassword).toString();

      let dataRec = new FormData();
      dataRec.append("emailUser", this.Rec.emailUser);
      dataRec.append("new_password_email", randomPassword); // Enviar senha para o email ao backend
      dataRec.append("new_password", encryptedPassword); // Enviar senha criptografada ao backend
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/loginRecover.php?action=recover",
          dataRec
        )
        .then((res) => {
          if (res.data.error === true) {
            this.recoverMessage = res.data.msg;
            this.fadeOutErrorRecMsg();
          } else {
            this.recoverMessage = res.data.msg;
            this.fadeOutSuccessRecMsg();
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },
    //*********Função p/ gerar senha aleatória*********//
    generateRandomPassword() {
      const length = 12; // Comprimento da senha
      const charset =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~";
      let password = "";
      for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
      }
      return password;
    },
    //*********Função p/ msg de erro no login*********//
    fadeOutErrorMessage() {
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
    },
    //*********Derrubar sessão ativa*********//
    confirmActiveSessionOverride() {
      let username = this.User.username;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/overrideSession.php?action=derrubarLogin&username=${username}`
        )
        .then((res) => {
          if (res.data.error) {
            alert(res.data.msg);
          } else {
            this.activeSessionModal.hide(); // Fecha o modal
            this.$router.push("/Home");
            localStorage.setItem("token", this.sessionToken);
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },
    //*********Função p/ msg de erro ao recuperar login*********//
    fadeOutErrorRecMsg() {
      let opacity = 1;
      const interval = setInterval(() => {
        opacity -= 0.1;
        this.recoverMessageOpacity = opacity;
        if (opacity <= 0) {
          clearInterval(interval);
          this.recoverMessage = "";
          this.recoverMessageOpacity = 1;
        }
      }, 800);
    },
    //*********Função p/ msg de sucesso ao recuperar login*********//
    fadeOutSuccessRecMsg() {
      let opacity = 1;
      const interval = setInterval(() => {
        opacity -= 0.1;
        this.recoverMessageOpacity = opacity;
        if (opacity <= 0) {
          clearInterval(interval);
          this.recoverMessage = "";
          this.recoverMessageOpacity = 1;
        }
      }, 1000);
    },
  },
};
</script>
<style>
@import "../assets/css/component/LoginForm.css";
</style>
