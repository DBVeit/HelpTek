<template>
  <div id="div_config_usuarios">
    <div class="message-box" v-if="showMessage">
      <div class="message-content">
        <span>{{ message }}</span>
      </div>
    </div>
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
              <th>Permissão</th>
              <th>Data de criação</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="usuarios in Usuarios" :key="usuarios.id_user">
              <td>{{ usuarios.idfr_code_user }}</td>
              <td>{{ usuarios.name_user }}</td>
              <td>{{ usuarios.username_user }}</td>
              <td>{{ usuarios.permissao_user_desc }}</td>
              <td>{{ usuarios.data_criacao_fm }}</td>
              <td>{{ usuarios.status_user_desc }}</td>
              <td>
                <button
                  class="bt-chamado"
                  data-bs-toggle="modal"
                  data-bs-target="#myModal"
                  @click="verUsuario(usuarios)"
                >
                  Ver
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--Modal p/ visualizar e editar usuarios-->
      <div class="modal fade bd-example-modal-lg" id="myModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="width: 70%">
              <h4 class="modal-title">Dados do usuário</h4>
              <button
                type="button"
                class="btn-close"
                style="padding: 5px"
                data-bs-dismiss="modal"
              ></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form method="POST" @submit.prevent="">
                <div class="form-group-modal">
                  <label>Nome</label>
                  <input type="text" v-model="UsuarioData.name_user" disabled />
                </div>
                <div class="form-group-modal">
                  <label>Primeiro Nome</label>
                  <input
                    type="text"
                    v-model="UsuarioData.first_name"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>E-Mail</label>
                  <input
                    type="text"
                    v-model="UsuarioData.email_user"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>Permissão</label>
                  <select v-model="UsuarioData.id_permissao" disabled>
                    <option
                      v-for="permissao in permissoesUsuario"
                      :key="permissao.id_permissao"
                      :value="permissao.id_permissao"
                    >
                      {{ permissao.descricao_permissao }}
                    </option>
                  </select>
                </div>
                <div class="form-group-modal">
                  <label>Usuário</label>
                  <input
                    type="text"
                    v-model="UsuarioData.username_user"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>Senha</label>
                  <input
                    type="password"
                    v-model="UsuarioData.password_user"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>Confirma senha</label>
                  <input
                    type="password"
                    v-model="UsuarioData.confirma_senha"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>Status</label>
                  <select>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                  </select>
                </div>
                <!--Botoes de ação-->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!---------------------------------Cadastrar usuario--------------------------------->
    <div v-if="!showList" id="div_form_cadastro">
      <h1>Cadastrar usuário</h1>
      <div class="form_cadastro">
        <form method="POST" @submit.prevent="onCriarUsuario()">
          <div class="form-group-users">
            <label>Nome Completo</label>
            <input
              type="text"
              name="nome"
              v-model="UsuarioData.name_user"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Apelido</label>
            <input
              type="text"
              name="primeiro_nome"
              v-model="UsuarioData.first_name"
              required
            />
          </div>
          <div class="form-group-users">
            <label>E-Mail</label>
            <input
              type="email"
              name="email"
              v-model="UsuarioData.email_user"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Permissão</label>
            <select
              name="permissao"
              id="permissao"
              v-model="UsuarioData.id_permissao"
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
              v-model="UsuarioData.username_user"
              required
            />
          </div>
          <div class="form-group-users">
            <label>Senha</label>
            <div class="password-input">
              <input
                :type="showPassword ? 'text' : 'password'"
                name="senha"
                v-model="UsuarioData.password_user"
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
import axios from "axios";
import CryptoJS from "crypto-js";

export default {
  name: "ConfiguracoesUsuarios",
  data() {
    return {
      showList: true,
      showPassword: false,
      permissoesUsuario: [],
      UsuarioData: {
        id_user: "",
        idfr_code_user: "",
        name_user: "",
        first_name: "",
        email_user: "",
        id_permissao: "",
        username_user: "",
        password_user: "",
        confirma_senha: "",
        data_criacao: "",
        status_user: "",
      },
      Usuarios: [],
      showMessage: false,
      message: "",
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    import("../assets/css/component/ConfiguracoesUsuarios.css");
    this.onListarUsuarios();
    this.fetchPermissoes();
  },
  methods: {
    onListarUsuarios() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectUsuarios.php?action=selectUsuarios`
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.Usuarios = res.data.usuarios;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    verUsuario(usuario) {
      this.UsuarioData = usuario;
      this.isEditing = false;
    },
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

      // eslint-disable-next-line no-undef
      const encryptedPassword = CryptoJS.SHA256(
        this.UsuarioData.password_user
      ).toString();

      // eslint-disable-next-line no-undef
      const encryptedPasswordConf = CryptoJS.SHA256(
        this.UsuarioData.confirma_senha
      ).toString();

      data.append("nome", this.UsuarioData.name_user);
      data.append("primeiro_nome", this.UsuarioData.first_name);
      data.append("email", this.UsuarioData.email_user);
      data.append("permissao", this.UsuarioData.id_permissao);
      data.append("user", this.UsuarioData.username_user);
      data.append("senha", encryptedPassword);
      data.append("confirma_senha", encryptedPasswordConf);
      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/insertUsuario.php?action=InsertUsuario",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error == true) {
            this.showAlert(res.data.msg);
            //this.clearFormFields();
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
  },
};
</script>
