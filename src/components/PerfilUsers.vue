<template>
  <div class="ticket-form-container">
    <h1>Perfil</h1>
    <div class="message-box" v-if="showMessage">
      <div class="message-content">
        <span>{{ message }}</span>
      </div>
    </div>
    <div>
      <div style="margin-top: 30px; margin-left: 20px">
        <a href="#" @click.prevent="exibeForm()">Alterar senha</a>
      </div>
      <div v-if="alteraSenhaForm" style="margin-left: 60px">
        <form method="POST" @submit.prevent="onRedefinirSenha()">
          <div class="form-group">
            <div class="form-group">
              <label>Senha *</label>
              <input
                :type="showPassword ? 'text' : 'password'"
                class="form-control"
                name="senha"
                v-model="Usuario.password_user"
                @input="validatePassword"
              />
              <span class="form-danger-msg"
                >*A senha deve conter ter no mínimo 8 caracteres, considerando
                letras maiúsculas e minúsculas, números e caracteres especiais.
              </span>
              <i
                v-if="Usuario.password_user"
                :class="passwordValidationMessage"
              ></i>
              <span
                class="form-danger-msg"
                v-if="!Usuario.password_user && showErrors"
                ><br />*Preechimento obrigatório!
              </span>
            </div>
            <div class="form-group">
              <label>Confirmar Senha *</label>
              <input
                :type="showPassword ? 'text' : 'password'"
                class="form-control"
                name="confirma_senha"
                v-model="Usuario.confirma_senha"
                @input="validatePasswordMatch"
              />
              <span
                class="form-danger-msg"
                v-if="!Usuario.confirma_senha && showErrors"
                >*Preechimento obrigatório!</span
              >
              <span class="form-danger-msg" v-if="passwordMatchMessage">{{
                passwordMatchMessage
              }}</span>
              <br />
              <div class="form-check">
                <input
                  type="checkbox"
                  class="form-check-input check-passw"
                  id="togglePasswordVisibility_pass"
                  v-model="showPassword"
                />
                <label
                  class="form-check-label check-passw"
                  for="togglePasswordVisibility_pass"
                >
                  Mostrar senha
                </label>
              </div>
            </div>
            <div class="confirmation-overlay">
              <br />
              <div class="confirmation-box">
                <button type="submit" class="submit-button">
                  Salvar Alterações
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
//import axios from "axios";

import CryptoJS from "crypto-js";
import axios from "axios";

export default {
  name: "PerfilUsers",
  data() {
    return {
      showPassword: false,
      showMessage: false,
      message: "",
      showErrors: false,
      passwordValidationMessage: "",
      passwordMatchMessage: "",
      isEditing: false,
      originalUsuario: {},
      usuarioAtual: {},
      validaSenha: false,
      matchSenha: false,
      alteraSenhaForm: false,
      Usuario: {
        password_user: "",
        confirma_senha: "",
      },
    };
  },
  methods: {
    // Validação do padrão de senha
    validatePassword() {
      const password = this.Usuario.password_user;
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
    },
    // Validação de senhas inseridas / comparação
    validatePasswordMatch() {
      if (this.Usuario.password_user !== this.Usuario.confirma_senha) {
        this.passwordMatchMessage = "As senhas não coincidem.";
        this.matchSenha = false;
      } else {
        this.passwordMatchMessage = "";
        this.matchSenha = true;
      }
    },
    //
    //Redefinir senha de usuario
    onRedefinirSenha() {
      let data = new FormData();

      this.showErrors = true;

      if (!this.Usuario.password_user || !this.Usuario.confirma_senha) {
        // Não prosseguir se houver erros
        return;
      } else if (this.validaSenha == false || this.matchSenha == false) {
        // Validação de senha
        return;
      }

      // Encriptação de senha no front
      // eslint-disable-next-line no-undef
      const encryptedPassword = CryptoJS.SHA256(
        this.Usuario.password_user
      ).toString();

      // eslint-disable-next-line no-undef
      const encryptedPasswordConf = CryptoJS.SHA256(
        this.Usuario.confirma_senha
      ).toString();

      let id_user_session = sessionStorage.getItem("id_user");
      let session_token = localStorage.getItem("token");

      data.append("id_user", id_user_session);
      data.append("encryptedPassword", encryptedPassword);
      data.append("encryptedPasswordConf", encryptedPasswordConf);
      data.append("tela", "Perfil");

      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/session/checkUser.php?id_user=${id_user_session}&session_token=${session_token}`
        )
        .then((res) => {
          if (res.data.error === false) {
            console.log("Server response:", res.data.msg);
            this.showAlert(res.data.msg);
            //this.clearFormFields();
            //const idfr_code_user = res.data.user;
            //data.append("idfr_code_user", idfr_code_user);
            axios
              .post(
                "http://localhost/projeto/helptek/php/api/functions/admin/redefineSenha.php",
                data
              )
              .then((res_redef) => {
                console.log("Server response:", res_redef.data);
                if (res_redef.data.error === true) {
                  this.showAlert(res_redef.data.msg);
                } else {
                  this.showAlert(res_redef.data.msg);
                  this.clearFormFields();
                }
              })
              .catch((err) => {
                console.log(err);
              });
            //this.showAlert(res.data.msg);
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    clearFormFields() {
      this.Usuario.password_user = "";
      this.Usuario.confirma_senha = "";
      this.showErrors = false;
    },
    exibeForm() {
      this.alteraSenhaForm = true;
    },
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
  },
};
</script>
