<template>
  <div class="ticket-form-container">
    <h1>Meus chamados</h1>
    <div class="filter">
      Filtros:
      <select v-model="selectedStatus" @change="filterChamados">
        <option selected disabled>Selecionar...</option>
        <option value="1">Em aberto</option>
        <option value="2">Em atendimento</option>
        <option value="3">Respondido</option>
        <option value="4">Concluido</option>
        <option value="0">Cancelado</option>
      </select>
      <a href="" @click.prevent="limparFiltros" v-if="selectedStatus !== null"
        >Limpar filtros</a
      >
    </div>
    <div>
      <table class="chamados-list-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titulo</th>
            <th>Prioridade</th>
            <th>Data de criação</th>
            <th>Status</th>
            <th>Atualizado em</th>
            <th>Concluído em</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="chamados in Chamados" :key="chamados.id_chamado">
            <td>{{ chamados.idfr_chamado }}</td>
            <td class="title">{{ chamados.titulo_chamado }}</td>
            <td>{{ chamados.prioridade_chamado_desc }}</td>
            <td>{{ chamados.data_criacao_fm }}</td>
            <td>{{ chamados.status_chamado_desc }}</td>
            <td>{{ chamados.data_atualizacao_fm }}</td>
            <td>{{ chamados.data_conclusao_fm }}</td>
            <td>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalVisualizarEditarChamado"
                @click="verChamado(chamados)"
                title="Ver/Editar"
              >
                <i class="bi bi-eye"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalAvaliarResposta"
                @click="verChamado(chamados)"
                title="Avaliar resposta"
              >
                <i class="bi bi-star-half"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalCancelarChamado"
                @click="verUsuario(usuarios)"
                title="Cancelar"
              >
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="recover-message" v-if="recoverMessage">
        {{ recoverMessage }}
      </div>
    </div>
    <!----------------------Modal p/ visualizar e editar informações do chamado---------------------->
    <div
      class="modal fade bd-example-modal-lg"
      id="modalVisualizarEditarChamado"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header" style="width: 70%">
            <h4 class="modal-title">Dados do chamado</h4>
            <button
              type="button"
              class="btn-close"
              style="padding: 5px"
              data-bs-dismiss="modal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
            <form method="POST" @submit.prevent="">
              <div class="form-group-modal">
                <label>Título</label>
                <input
                  type="text"
                  id="subject"
                  v-model="ChamadoData.titulo_chamado"
                  :disabled="!isEditing"
                />
              </div>
              <div class="form-group-modal">
                <label>Descrição</label>
                <textarea
                  id="description"
                  rows="4"
                  v-model="ChamadoData.descricao_chamado"
                  :disabled="!isEditing"
                ></textarea>
              </div>
              <div class="form-group-modal">
                <label>Prioridade</label>
                <select v-model="ChamadoData.gravidade" :disabled="!isEditing">
                  <option
                    v-for="gravidade in prioridadesGravidade"
                    :key="gravidade.id_prioridade"
                    :value="gravidade.valor_prioridade"
                  >
                    {{ gravidade.descricao_categoria }}
                  </option>
                </select>
                <select v-model="ChamadoData.urgencia" :disabled="!isEditing">
                  <option
                    v-for="urgencia in prioridadesUrgencia"
                    :key="urgencia.id_prioridade"
                    :value="urgencia.valor_prioridade"
                  >
                    {{ urgencia.descricao_categoria }}
                  </option>
                </select>
                <select v-model="ChamadoData.tendencia" :disabled="!isEditing">
                  <option
                    v-for="tendencia in prioridadesTendencia"
                    :key="tendencia.id_prioridade"
                    :value="tendencia.valor_prioridade"
                  >
                    {{ tendencia.descricao_categoria }}
                  </option>
                </select>
              </div>
              <div>
                <label>Anexos</label>
              </div>
              <div class="confirmation-overlay" v-if="!isEditing">
                <div class="confirmation-box">
                  <button type="submit" class="btEditar" @click="editarChamado">
                    Editar
                  </button>
                </div>
              </div>
              <div v-else>
                <button
                  type="button"
                  class="submit-button-modal"
                  @click="salvarChamado"
                >
                  Salvar
                </button>
              </div>
            </form>
          </div>
          <!-- Confirmação de Cancelamento -->
          <div v-if="isConfirmingCancel" class="confirmation-overlay">
            <div class="confirmation-box">
              <p>Deseja cancelar o chamado?</p>
              <button @click="cancelarChamado" class="cancelaSim">Sim</button>
              <button @click="isConfirmingCancel = false" class="cancelaNao">
                Não
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!----------------------Modal p/ visualizar e editar informações do chamado---------------------->
    <!-----------------------Modal p/ visualizar e avaliar resposta do chamado----------------------->
    <div class="modal fade bd-example-modal-lg" id="modalAvaliarResposta">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="width: 70%">
            <h4 class="modal-title">Avaliar resposta</h4>
            <button
              type="button"
              class="btn-close"
              style="padding: 5px"
              data-bs-dismiss="modal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
            <form method="POST" @submit.prevent="">
              <h5 class="modal-title">Retorno do atendimento</h5>
              <div class="form-group-modal">
                <label>Categoria do Serviço</label>
                <select v-model="ChamadoData.id_categoria_servico" disabled>
                  <option
                    v-for="categoriaServ in categoriasServico"
                    :key="categoriaServ.id_categoria_servico"
                    :value="categoriaServ.id_categoria_servico"
                  >
                    {{ categoriaServ.descricao_categoria_servico }}
                  </option>
                </select>
              </div>
              <div class="form-group-modal">
                <label>Categoria da Ocorrência</label>
                <select v-model="ChamadoData.id_categoria_ocorrencia" disabled>
                  <option
                    v-for="categoriaOcor in categoriasOcorrencia"
                    :key="categoriaOcor.id_categoria_ocorrencia"
                    :value="categoriaOcor.id_categoria_ocorrencia"
                  >
                    {{ categoriaOcor.descricao_categoria_ocorrencia }}
                  </option>
                </select>
              </div>
              <div class="form-group-modal">
                <label>Descrição da Solução</label>
                <textarea
                  v-model="ChamadoData.descricao_solucao"
                  disabled
                ></textarea>
              </div>
              <div class="confirmation-overlay" v-if="!isEditing">
                <div class="confirmation-box">
                  <button type="submit" class="btEditar" @click="editarChamado">
                    Avaliar
                  </button>
                </div>
              </div>
              <div v-else>
                <button
                  type="button"
                  class="submit-button-modal"
                  @click="salvarChamado"
                >
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-----------------------Modal p/ visualizar e avaliar resposta do chamado----------------------->
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalCancelarChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="width: 70%">
            <h4 class="modal-title">Cancelar chamado</h4>
            <button
              type="button"
              class="btn-close"
              style="padding: 5px"
              data-bs-dismiss="modal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
          </div>
          <!-- Confirmação de Cancelamento -->
          <div v-if="isConfirmingCancel" class="confirmation-overlay">
            <div class="confirmation-box">
              <p>Deseja cancelar o chamado?</p>
              <button @click="cancelarChamado" class="cancelaSim">Sim</button>
              <button @click="isConfirmingCancel = false" class="cancelaNao">
                Não
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
  </div>
</template>
<script>
import axios from "axios";
import $ from "jquery";

export default {
  name: "MeusChamados",
  data() {
    return {
      ChamadoData: {
        id_chamado: "",
        id_user: "",
        idfr_chamado: "",
        titulo_chamado: "",
        descricao_chamado: "",
        prioridade_chamado: "",
        data_criacao: "",
        status_chamado: "",
        data_atualizacao: "",
        data_conclusao: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_categoria_servico: "",
        id_categoria_ocorrencia: "",
        categoriaServico: "",
        categoriaOcorrencia: "",
        descricao_solucao: "",
        valor: "",
      },
      Chamados: [],
      selectedStatus: null,
      isEditing: false,
      isConfirmingCancel: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      categoriasServico: [],
      categoriasOcorrencia: [],
      recoverMessage: "",
      showMessage: false,
      message: "",
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.fetchPrioridades();
    this.fetchCategoriasServico();
    this.fetchCategoriasOcorrencia();
  },
  methods: {
    onListarChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectChamados.php?action=selectChamados&id_user=${id_user}&permission=${permission}`
        )
        .then((res) => {
          if (res.data.error === true) {
            console.log("Server response:", res.data.msg);
            this.recoverMessage = res.data.msg;
          } else {
            console.log("Server response:", res.data);
            this.Chamados = res.data.chamados;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fetchPrioridades() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/selectPrioridades.php"
        )
        .then((response) => {
          const prioridades = response.data.prioridades;
          this.prioridadesGravidade = prioridades.filter(
            (p) => p.categoria_prioridade === "gravidade"
          );
          this.prioridadesUrgencia = prioridades.filter(
            (p) => p.categoria_prioridade === "urgencia"
          );
          this.prioridadesTendencia = prioridades.filter(
            (p) => p.categoria_prioridade === "tendencia"
          );
        })
        .catch((error) => {
          console.error("Erro ao buscar prioridades: ", error);
        });
    },
    fetchCategoriasServico() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/selectCategoriasServico.php"
        )
        .then((response) => {
          if (!response.data.error) {
            this.categoriasServico = response.data.categorias_servico;
          } else {
            console.error(
              "Erro ao buscar categorias do serviço:",
              response.data.msg
            );
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar categorias do serviço:", error);
        });
    },
    fetchCategoriasOcorrencia() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/selectCategoriasOcorrencia.php"
        )
        .then((response) => {
          if (!response.data.error) {
            this.categoriasOcorrencia = response.data.categorias_ocorrencia;
          } else {
            console.error(
              "Erro ao buscar categorias de ocorrencia:",
              response.data.msg
            );
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar categorias de ocorrencia:", error);
        });
    },
    verChamado(chamado) {
      this.ChamadoData = chamado;
      this.isEditing = false;
      this.ChamadoData.gravidade = chamado.gravidade;
      this.ChamadoData.urgencia = chamado.urgencia;
      this.ChamadoData.tendencia = chamado.tendencia;
    },
    editarChamado() {
      this.isEditing = true;
    },
    salvarChamado() {
      const {
        id_chamado,
        id_user = sessionStorage.getItem("id_user"),
        idfr_chamado,
        titulo_chamado,
        descricao_chamado,
        gravidade,
        urgencia,
        tendencia,
      } = this.ChamadoData;
      /*console.log("Dados do chamado:", {
        id_chamado,
        id_user,
        idfr_chamado,
        titulo_chamado,
        descricao_chamado,
        gravidade,
        urgencia,
        tendencia,
      });*/
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/updateChamado.php?action=AtualizaChamado`,
          {
            id_chamado,
            id_user,
            idfr_chamado,
            titulo_chamado,
            descricao_chamado,
            gravidade,
            urgencia,
            tendencia,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error === true) {
            this.showAlert(res.data.msg);
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    confirmarCancelamento() {
      this.isConfirmingCancel = true;
    },
    cancelarChamado() {
      const {
        id_chamado,
        id_user = sessionStorage.getItem("id_user"),
        idfr_chamado,
      } = this.ChamadoData;
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/cancelaChamado.php?action=CancelaChamado`,
          {
            id_chamado,
            id_user,
            idfr_chamado,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.isConfirmingCancel = false;
          this.showAlert(res.data.msg);
          this.onListarChamados();
          $("#myModal").modal("hide");
        })
        .catch((error) => {
          console.error("Erro ao cancelar chamado:", error);
        });
    },
    filterChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      if (!this.selectedStatus) return;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectChamados.php?action=selectChamados&id_user=${id_user}&permission=${permission}&status_chamado=${this.selectedStatus}`
        )
        //.get(`/api/chamados?status_chamado=${this.selectedStatus}`)
        .then((response) => {
          this.Chamados = response.data.chamados;
        })
        .catch((error) => {
          console.error("Erro ao buscar chamados:", error);
        });
    },
    limparFiltros() {
      this.selectedStatus = null;
      this.onListarChamados();
      // Limpar a lista de chamados filtrados
    },
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    clearFormFields() {
      this.ChamadoData.titulo = "";
      this.ChamadoData.descricao = "";
      this.ChamadoData.gravidade = "";
      this.ChamadoData.urgencia = "";
      this.ChamadoData.tendencia = "";
      this.$refs.attachment.value = "";
    },
    closeMessage() {
      this.showMessage = false;
    },
  },
  /*computed: {
    prioridadeTexto() {
      const prioridade = this.ChamadoData.prioridade_chamado;
      if (prioridade <= 20) {
        return "Baixa";
      } else if (prioridade <= 60) {
        return "Média";
      } else if (prioridade <= 100) {
        return "Alta";
      } else {
        return "Crítica";
      }
    },
  },*/
};
</script>
