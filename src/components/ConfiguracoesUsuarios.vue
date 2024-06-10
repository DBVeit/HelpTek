<template>
  <div id="div_config_usuarios">
    <div v-if="showList" id="div_list_usuarios">
      <h1>Configurações de usuários</h1>
      <div class="actions_ConfigUser">
        <button @click="toggleView" class="btn_CadastrarUsuario">
          Cadastrar usuário
        </button>
      </div>
      <div>
        <table class="usuarios-list-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Usuário</th>
              <th>E-Mail</th>
              <th>Permissão</th>
              <th>Data de criação</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>ID</td>
              <td>Nome</td>
              <td>Usuário</td>
              <td>E-Mail</td>
              <td>Permissão</td>
              <td>Data de criação</td>
              <td>Status</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!--Cadastrar usuario-->
    <div v-if="!showList" id="div_form_cadastro">
      <h1>Cadastrar usuário</h1>
      <div class="form_cadastro">
        <form method="POST" @submit.prevent="onCriarUsuario()">
          <div class="form-group-users">
            <label>Nome Completo</label>
            <input
              type="text"
              name="nome"
              v-model="UsuarioData.nome"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Primeiro Nome</label>
            <input
              type="text"
              name="primeiro_nome"
              v-model="UsuarioData.primeiro_nome"
              required
            />
          </div>
          <div class="form-group-users">
            <label>E-Mail</label>
            <input
              type="email"
              name="email"
              v-model="UsuarioData.email"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Permissão</label>
            <select
              name="permissao"
              id="permissao"
              v-model="UsuarioData.permissao"
              required
            >
              <option default disabled>Permissao</option>
              <option
                v-for="permissao in permissoesUsuario"
                :key="permissao.id_permissao"
                :value="permissao.id_permissao"
              >
                {{ permissao.descricao_permissao }}
              </option>
            </select>
          </div>
          <div class="form-group-users">
            <label>Usuário</label>
            <input
              type="text"
              name="usuario"
              v-model="UsuarioData.user"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Senha</label>
            <div class="password-input">
              <input
                :type="showPassword ? 'text' : 'password'"
                name="senha"
                v-model="UsuarioData.senha"
                required
              />
              <button
                type="button"
                class="toggle-pass"
                @click="togglePasswordVisibility"
              >
                {{ showPassword ? "Esconder" : "Mostrar" }}
              </button>
            </div>
          </div>
          <div class="form-group-users">
            <label>Confirmar Senha</label>
            <div class="password-input">
              <input
                :type="showPassword ? 'text' : 'password'"
                name="confirma_senha"
                v-model="UsuarioData.confirma_senha"
                required
              />
              <button
                type="button"
                class="toggle-pass"
                @click="togglePasswordVisibility"
              >
                {{ showPassword ? "Esconder" : "Mostrar" }}
              </button>
            </div>
          </div>
          <div class="form-buttons">
            <button type="submit" class="submit-button">
              Confirmar Cadastro
            </button>
            <button type="button" @click="toggleView" class="cancel-button">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
//import axios from "axios";

import axios from "axios";

export default {
  name: "ConfiguracoesUsuarios",
  data() {
    return {
      showList: true,
      showPassword: false,
      permissoesUsuario: [],
      UsuarioData: {
        nome: "",
        primeiro_nome: "",
        email: "",
        permissao: "",
        user: "",
        senha: "",
        confirma_senha: "",
      },
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    import("../assets/css/component/ConfiguracoesUsuarios.css");
    this.fetchPermissoes();
  },
  methods: {
    toggleView() {
      this.showList = !this.showList;
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    submit() {
      if (!this.validatePassword(this.UsuarioData.senha)) {
        alert(
          "A senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas e minúsculas, números e caracteres especiais."
        );
        return;
      }

      if (this.UsuarioData.senha !== this.UsuarioData.confirma_senha) {
        alert("As senhas não coincidem.");
        return;
      }

      // Submit form data
      console.log("Form submitted", this.UsuarioData);
    },
    validatePassword(password) {
      const passwordRegex =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
      return passwordRegex.test(password);
    },
    fetchPermissoes() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/selectPermissoes.php"
        )
        .then((response) => {
          if (!response.data.error) {
            this.permissoesUsuario = response.data.permissoes;
          } else {
            console.error("Erro ao carregar permissões: ", response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao carregar permissões: ", error);
        });
    },
    onCriarUsuario() {
      let data = new FormData();
      data.append("nome", this.UsuarioData.nome);
      data.append("primeiro_nome", this.UsuarioData.primeiro_nome);
      data.append("email", this.UsuarioData.email);
      data.append("permissao", this.UsuarioData.permissao);
      data.append("user", this.UsuarioData.user);
      data.append("senha", this.UsuarioData.senha);
      data.append("confirma_senha", this.UsuarioData.confirma_senha);
      console.log(data);
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/insertUsuario.php?action=InsertUsuario",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error == true) {
            alert(res.data.msg);
          } else {
            alert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>
