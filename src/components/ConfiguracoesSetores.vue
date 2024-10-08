<template>
  <div id="div_config_usuarios">
    <div class="message-box" v-if="showMessage">
      <div class="message-content">
        <span>{{ message }}</span>
      </div>
    </div>
    <div v-if="showList" id="div_list_usuarios">
      <h1>Configurações setor</h1>
      <div class="actions_ConfigUser">
        <button
          class="submit-button"
          data-bs-toggle="modal"
          data-bs-target="#modalCadastroSetor"
          @click="abrirModal"
        >
          Cadastrar setor
        </button>
      </div>
      <div>
        <table class="usuarios-list-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Setor</th>
              <th>Peso</th>
              <th>Corporação</th>
              <th>Data de criação</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="setores in Setor" :key="setores.id_setor">
              <td>{{ setores.idfr_setor }}</td>
              <td>{{ setores.nome_setor }}</td>
              <td>{{ setores.peso }}</td>
              <td>{{ setores.idf_corp }}</td>
              <td>{{ setores.data_criacao_fm }}</td>
              <td>
                <button
                  class="bt-users-actions"
                  data-bs-toggle="modal"
                  data-bs-target="#myModal"
                  @click="
                    verSetor(setores);
                    abrirModal();
                  "
                  title="Ver/Editar"
                >
                  <i class="bi bi-eye"></i>
                </button>
                <button
                  class="bt-users-actions"
                  data-bs-toggle="modal"
                  data-bs-target="#modalDesativaAtivaSetor"
                  @click="verSetor(setores)"
                  title="Desativar usuário"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-building-dash"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1"
                    />
                    <path
                      d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"
                    />
                    <path
                      d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"
                    />
                  </svg>
                  <i class="bi bi-building-dash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="noDataFound === true" class="no-data-found">
        <span>{{ noDataFoundMsg }}</span>
      </div>
    </div>
    <!---------------------------------Modal Cadastrar Setor--------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalCadastroSetor">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cadastrar setor</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="message-box" v-if="showMessage">
            <div class="message-content">
              <span>{{ message }}</span>
            </div>
          </div>
          <div class="modal-body">
            <form method="POST" @submit.prevent="onCriarSetor()">
              <div class="form-group-modal">
                <label>Nome Setor *</label>
                <input
                  type="text"
                  class="form-control"
                  name="nome_setor"
                  v-model="NovoSetor.nome_setor"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoSetor.nome_setor && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Peso *</label>
                <input
                  type="number"
                  class="form-control"
                  max="5"
                  min="0"
                  name="peso"
                  v-model="NovoSetor.peso"
                />
                <span
                  class="form-danger-msg"
                  v-if="!NovoSetor.peso && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Corporação *</label>
                <select v-model="NovoSetor.corporacao">
                  <option default value="" disabled>Corporacao</option>
                  <option
                    v-for="corps in corporacoes"
                    :key="corps.id_corporacao"
                    :value="corps.id_corporacao"
                  >
                    {{ corps.nome_corporacao }}
                  </option>
                </select>
                <span
                  class="form-danger-msg"
                  v-if="!NovoSetor.corporacao && showErrors"
                  >*Preechimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Subsetor</label>
                <select
                  name="subsetor"
                  id="subsetor"
                  v-model="NovoSetor.subsetor"
                >
                  <option default value="" disabled>Subsetor</option>
                  <option
                    v-for="subs in subsetores"
                    :key="subs.id_setor"
                    :value="subs.id_setor"
                  >
                    {{ subs.nome_setor }}
                  </option>
                </select>
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
    <!---------------------------------Modal Cadastrar Setor--------------------------------->
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "ConfiguracoesSetor",
  data() {
    return {
      showList: true,
      showPassword: false,
      SetorData: {
        id_setor: "",
        id_corporacao: "",
        idfr_setor: "",
        nome_setor: "",
        corporacao: "",
        peso: "",
        data_criacao: "",
      },
      NovoSetor: {
        nome_setor: "",
        peso: "",
        id_corporacao: "",
        subsetor: "",
      },
      Setor: [],
      subsetores: [],
      corporacoes: [],
      showMessage: false,
      message: "",
      showErrors: false,
      isEditing: false,
      originalUsuario: {},
      usuarioAtual: {},
      validaSenha: false,
      matchSenha: false,
      noDataFound: false,
      noDataFoundMsg: "",
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    import("../assets/css/component/ConfiguracoesUsuarios.css");
    this.onListarSetores();
    this.fetchSetoresList();
    this.fetchCorporacoes();
  },
  methods: {
    // Listagem de setores cadastrados na base
    onListarSetores() {
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/admin/selectSetor.php?action=selectSetor`
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.Setor = res.data.setores;
          if (res.data.code == 204) {
            this.noDataFound = true;
            this.noDataFoundMsg = res.data.msg;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    verSetor(setor) {
      this.SetorData = setor;
      this.isEditing = false;
      this.originalSetor = { setor };
      this.corporacaoSetor = { setor };
    },
    // Visualizar ou esconder listagem
    toggleView() {
      this.showList = !this.showList;
    },
    //Listar corporacoes
    fetchCorporacoes() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/admin/selectCorporacoes.php"
        )
        .then((response) => {
          if (!response.data.error) {
            this.corporacoes = response.data.corps;
          } else {
            console.error("Erro ao buscar corporacoes: ", response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar corporacoes:", error);
        });
    },
    //Listar subsetores
    fetchSetoresList() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/admin/selectSetoresList.php"
        )
        .then((response) => {
          if (!response.data.error) {
            this.subsetores = response.data.sub_setores;
          } else {
            console.error("Erro ao buscar setores: ", response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar setores:", error);
        });
    },
    // Formulario de cadastro
    onCriarSetor() {
      let data = new FormData();

      this.showErrors = true;

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.NovoSetor.nome_setor ||
        !this.NovoSetor.peso ||
        !this.NovoSetor.corporacao
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("nome_setor", this.NovoSetor.nome_setor);
      data.append("peso", this.NovoSetor.peso);
      data.append("corporacao", this.NovoSetor.corporacao);
      data.append("subsetor", this.NovoSetor.subsetor);

      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/admin/insertSetor.php?action=InsertSetor",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error == true) {
            this.showAlertError(res.data.msg);
            //this.clearFormFields();
          } else {
            this.showAlertSuccess(res.data.msg);
            this.closeModal(); // Fecha o modal
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    // Mensagem de retorno do back-end
    showAlertSuccess(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    // Mensagem de retorno do back-end
    showAlertError(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    // Limpar campos ao abrir modal
    resetNovoUsuario() {
      this.NovaCorp = {
        nome_corporacao: "",
      };
    },
    // Código para abrir o modal
    abrirModal() {
      this.resetNovoUsuario();
      this.showErrors = false;
      this.showPassword = false;
    },
    // Código para fechar o modal após cadastro
    closeModal() {
      const closeButton = document.querySelector(
        '#modalCadastroSetor [data-bs-dismiss="modal"], #myModal [data-bs-dismiss="modal"]'
      );
      if (closeButton) {
        closeButton.click();
      }
      this.onListarCorporacao();
      this.verCorporacao(this.CorporacaoData);
    },
  },
};
</script>
