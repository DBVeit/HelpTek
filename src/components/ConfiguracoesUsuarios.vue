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
        <button
          class="submit-button"
          data-bs-toggle="modal"
          data-bs-target="#modalCadastro"
          @click="abrirModalCadastro"
        >
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
                  class="bt-users-actions"
                  data-bs-toggle="modal"
                  data-bs-target="#myModal"
                  @click="verUsuario(usuarios)"
                  title="Ver/Editar"
                >
                  <i class="bi bi-eye"></i>
                </button>
                <button
                  class="bt-users-actions"
                  data-bs-toggle="modal"
                  data-bs-target="#modalRedefinirSenha"
                  @click="verUsuario(usuarios)"
                  title="Redefinir senha"
                >
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button
                  class="bt-users-actions"
                  data-bs-toggle="modal"
                  data-bs-target="#modalDesativaAtivaUsuario"
                  @click="verUsuario(usuarios)"
                  title="Desativar usuário"
                >
                  <i class="bi bi-person-dash-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-------------------------------Modal p/ visualizar e editar usuarios------------------------------>
      <div class="modal fade bd-example-modal-lg" id="myModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Dados do usuário</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form method="POST" @submit.prevent="">
                <div class="form-group-modal">
                  <label>Nome</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="UsuarioData.name_user"
                  />
                </div>
                <div class="form-group-modal">
                  <label>Primeiro Nome</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="UsuarioData.first_name"
                  />
                </div>
                <div class="form-group-modal">
                  <label>E-Mail</label>
                  <input
                    type="email"
                    class="form-control"
                    v-model="UsuarioData.email_user"
                  />
                </div>
                <div class="form-group-modal">
                  <label>Permissão</label>
                  <select
                    class="form-select"
                    v-model="UsuarioData.id_permissao"
                  >
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
                  <div class="form-buttons">
                    <button type="submit" class="submit-button">
                      Salvar Alterações
                    </button>
                    <button
                      type="button"
                      class="cancel-button"
                      data-bs-dismiss="modal"
                    >
                      Cancelar
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!---------------------------Modal p/ visualizar e editar usuarios----------------------------->
    <!---------------------------Modal p/ redefinicao de senha----------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalRedefinirSenha">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Redefinir senha</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <form method="POST" @submit.prevent="">
              <div class="form-group-modal">
                <div class="form-group-modal">
                  <label>Senha *</label>
                  <input
                    :type="showPassword ? 'text' : 'password'"
                    class="form-control"
                    name="senha"
                    v-model="NovoUsuario.password_user"
                    @input="validatePassword"
                  />
                  <span class="form-danger-msg"
                    >*A senha deve conter ter no mínimo 8 caracteres,
                    considerando letras maiúsculas e minúsculas, números e
                    caracteres especiais.
                  </span>
                  <i
                    v-if="NovoUsuario.password_user"
                    :class="passwordValidationMessage"
                  ></i>
                  <span
                    class="form-danger-msg"
                    v-if="!NovoUsuario.password_user && showErrors"
                    ><br />*Preechimento obrigatório!
                  </span>
                </div>
                <div class="form-group-modal">
                  <label>Confirmar Senha *</label>
                  <input
                    :type="showPassword ? 'text' : 'password'"
                    class="form-control"
                    name="confirma_senha"
                    v-model="NovoUsuario.confirma_senha"
                    @input="validatePasswordMatch"
                  />
                  <span
                    class="form-danger-msg"
                    v-if="!NovoUsuario.confirma_senha && showErrors"
                    >*Preechimento obrigatório!</span
                  >
                  <span class="form-danger-msg" v-if="passwordMatchMessage">{{
                    passwordMatchMessage
                  }}</span>
                </div>
                <div class="form-check">
                  <input
                    type="checkbox"
                    class="form-check-input check-passw"
                    id="togglePasswordVisibility"
                    v-model="showPassword"
                  />
                  <label
                    class="form-check-label check-passw"
                    for="togglePasswordVisibility"
                  >
                    Mostrar senha
                  </label>
                </div>
                <div class="form-buttons">
                  <button type="submit" class="submit-button">
                    Salvar Alterações
                  </button>
                  <button
                    type="button"
                    class="cancel-button"
                    data-bs-dismiss="modal"
                  >
                    Cancelar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!---------------------------Modal p/ redefinicao de senha----------------------------->
    <!---------------------------Modal p/ desativar/ativar usuario----------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalDesativaAtivaUsuario">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Desativar/Ativar usuário</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <form method="POST" @submit.prevent="">
              <!--Botoes de ação-->
            </form>
          </div>
        </div>
      </div>
    </div>
    <!---------------------------Modal p/ desativar/ativar usuario----------------------------->
    <!---------------------------------Modal Cadastrar usuario--------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalCadastro">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Cadastrar usuário</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <form method="POST" @submit.prevent="onCriarUsuario()">
              <div class="form-group-modal">
                <label>Nome Completo *</label>
                <input
                  type="text"
                  class="form-control"
                  name="nome"
                  v-model="NovoUsuario.name_user"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.name_user && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Apelido *</label>
                <input
                  type="text"
                  class="form-control"
                  name="primeiro_nome"
                  maxlength="15"
                  v-model="NovoUsuario.first_name"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.first_name && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>E-Mail *</label>
                <input
                  type="email"
                  class="form-control"
                  name="email"
                  v-model="NovoUsuario.email_user"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.email_user && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Permissão *</label>
                <select
                  class="form-select"
                  name="permissao"
                  id="permissao"
                  v-model="NovoUsuario.id_permissao"
                >
                  <option value="" disabled>Permissão</option>
                  <option
                    v-for="permissao in permissoesUsuario"
                    :key="permissao.id_permissao"
                    :value="permissao.id_permissao"
                  >
                    {{ permissao.descricao_permissao }}
                  </option>
                </select>
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.id_permissao && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Usuário *</label>
                <input
                  type="text"
                  class="form-control"
                  name="usuario"
                  v-model="NovoUsuario.username_user"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.username_user && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Senha *</label>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  class="form-control"
                  name="senha"
                  v-model="NovoUsuario.password_user"
                  @input="validatePassword"
                />
                <span class="form-danger-msg"
                  >*A senha deve conter ter no mínimo 8 caracteres, considerando
                  letras maiúsculas e minúsculas, números e caracteres
                  especiais.
                </span>
                <i
                  v-if="NovoUsuario.password_user"
                  :class="passwordValidationMessage"
                ></i>
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.password_user && showErrors"
                  ><br />*Preechimento obrigatório!
                </span>
              </div>
              <div class="form-group-modal">
                <label>Confirmar Senha *</label>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  class="form-control"
                  name="confirma_senha"
                  v-model="NovoUsuario.confirma_senha"
                  @input="validatePasswordMatch"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoUsuario.confirma_senha && showErrors"
                  >*Preechimento obrigatório!</span
                >
                <span class="form-danger-msg" v-if="passwordMatchMessage">{{
                  passwordMatchMessage
                }}</span>
              </div>
              <div class="form-check">
                <input
                  type="checkbox"
                  class="form-check-input check-passw"
                  id="togglePasswordVisibility"
                  v-model="showPassword"
                />
                <label
                  class="form-check-label check-passw"
                  for="togglePasswordVisibility"
                >
                  Mostrar senha
                </label>
              </div>
              <div class="form-group-modal">
                <div class="form-buttons">
                  <button type="submit" class="submit-button">
                    Confirmar Cadastro
                  </button>
                  <button
                    type="button"
                    class="cancel-button"
                    data-bs-dismiss="modal"
                  >
                    Cancelar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!---------------------------------Modal Cadastrar usuario--------------------------------->
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
      NovoUsuario: {
        name_user: "",
        first_name: "",
        email_user: "",
        id_permissao: "",
        username_user: "",
        password_user: "",
        confirma_senha: "",
      },
      Usuarios: [],
      showMessage: false,
      message: "",
      showErrors: false,
      passwordValidationMessage: "",
      passwordMatchMessage: "",
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    import("../assets/css/component/ConfiguracoesUsuarios.css");
    this.onListarUsuarios();
    this.fetchPermissoes();
  },
  methods: {
    // Listagem de usuarios cadastrados na base
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
    // Visualizar ou esconder listagem
    toggleView() {
      this.showList = !this.showList;
    },
    //Visualizar ou esconder a senha
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    // Listar permissoes de usuarios
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
    // Formulario de cadastro
    onCriarUsuario() {
      let data = new FormData();

      this.showErrors = true;

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.NovoUsuario.name_user ||
        !this.NovoUsuario.first_name ||
        !this.NovoUsuario.email_user ||
        !this.NovoUsuario.id_permissao ||
        !this.NovoUsuario.username_user ||
        !this.NovoUsuario.password_user ||
        !this.NovoUsuario.confirma_senha
      ) {
        // Não prosseguir se houver erros
        return;
      }

      // Validação de senha
      if (this.passwordValidationMessage || this.passwordMatchMessage) {
        return;
      }

      // Encriptação de senha no front
      // eslint-disable-next-line no-undef
      const encryptedPassword = CryptoJS.SHA256(
        this.NovoUsuario.password_user
      ).toString();

      // eslint-disable-next-line no-undef
      const encryptedPasswordConf = CryptoJS.SHA256(
        this.NovoUsuario.confirma_senha
      ).toString();

      data.append("nome", this.NovoUsuario.name_user);
      data.append("primeiro_nome", this.NovoUsuario.first_name);
      data.append("email", this.NovoUsuario.email_user);
      data.append("permissao", this.NovoUsuario.id_permissao);
      data.append("user", this.NovoUsuario.username_user);
      data.append("senha", encryptedPassword);
      data.append("confirma_senha", encryptedPasswordConf);
      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      //console.log(dataEntries); // Exibe o objeto com os dados
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
            this.closeModal(); // Fecha o modal
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    // Mensagem de retorno do back-end
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    // Validação do padrão de senha
    validatePassword() {
      const password = this.NovoUsuario.password_user;
      const passwordRegex =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

      if (!password) {
        this.passwordValidationMessage = "";
      } else if (!passwordRegex.test(password)) {
        this.passwordValidationMessage = "bi bi-x text-danger";
      } else {
        this.passwordValidationMessage = "bi bi-check2 text-success";
      }
    },
    // Validação de senhas inseridas / comparação
    validatePasswordMatch() {
      if (this.NovoUsuario.password_user !== this.NovoUsuario.confirma_senha) {
        this.passwordMatchMessage = "As senhas não coincidem.";
      } else {
        this.passwordMatchMessage = "";
      }
    },
    // Limpar campos ao abrir modal
    resetNovoUsuario() {
      this.NovoUsuario = {
        name_user: "",
        first_name: "",
        email_user: "",
        id_permissao: "",
        username_user: "",
        password_user: "",
        confirma_senha: "",
      };
    },
    // Código para abrir o modal
    abrirModalCadastro() {
      this.resetNovoUsuario();
      this.showErrors = false;
      this.showPassword = false;
      this.passwordMatchMessage = "";
    },
    // Código para fechar o modal após cadastro
    closeModal() {
      const closeButton = document.querySelector(
        '#modalCadastro [data-bs-dismiss="modal"]'
      );
      if (closeButton) {
        closeButton.click();
      }
      this.onListarUsuarios();
      this.showPassword = false;
      this.passwordMatchMessage = "";
    },
  },
};
</script>
