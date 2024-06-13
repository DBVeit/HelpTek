<template>
  <div class="container">
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
        <button type="submit">Entrar</button>
      </form>
    </div>
    <div
      class="recover-message"
      v-if="recoverMessage"
      :style="{ opacity: recoverMessageOpacity }"
    >
      {{ recoverMessage }}
    </div>
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
        <button type="submit">Enviar</button>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
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
    };
  },
  methods: {
    onLogin() {
      if (!this.User.username || !this.User.password) {
        //alert("Por favor, preencha todos os campos.");
        this.errorMessage = "Por favor, preencha todos os campos.";
        return;
      }

      let data = new FormData();
      data.append("username", this.User.username);
      data.append("password", this.User.password);
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/login.php?action=login",
          data
        )
        .then((res) => {
          console.log(res.data); // REMOVER
          console.log("Server response:", res.data.code); // REMOVER
          if (res.data.code !== 200) {
            //console.log("Error: ", res.data.code);
            //alert(res.data.msg);
            this.errorMessage = res.data.msg;
            this.fadeOutErrorMessage();
          } else {
            //console.log("Success", res.data.code);
            //alert(res.data.msg);
            this.$router.push("/Home");
            localStorage.setItem("token", res.data.token);
            sessionStorage.setItem("id_user", res.data.id_user);
            sessionStorage.setItem("first_name", res.data.first_name);
            sessionStorage.setItem("level_user", res.data.level_user);
            sessionStorage.setItem("permission", res.data.user_permission);
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },
    onRecovery() {
      if (!this.Rec.emailUser) {
        alert("Por favor, preencha o campo.");
        return;
      }
      let dataRec = new FormData();
      dataRec.append("emailUser", this.Rec.emailUser);
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/loginRecover.php?action=recover",
          dataRec
        )
        .then((res) => {
          console.log("Server response:", res.data); // REMOVER
          if (res.data.error === true) {
            //console.log("Error: ", res.data.code);
            this.recoverMessage = res.data.msg;
            this.fadeOutErrorRecMsg();
            //alert(res.data.msg);
          } else {
            //console.log("Success", res.data.code);
            this.recoverMessage = res.data.msg;
            this.fadeOutSuccessRecMsg();
            //alert(res.data.msg);
            //this.$router.push("/");
          }
        })
        .catch((err) => {
          console.log("Err", err);
        });
    },
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
      }, 200);
    },
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
      }, 300);
    },
  },
};
</script>
<style>
@import "../assets/css/component/LoginForm.css";
</style>
