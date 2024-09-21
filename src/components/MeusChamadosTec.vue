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
            <th>Prazo</th>
            <!--<th>Atualizado em</th>
              <th>Concluído em</th>-->
            <th>Última atualização</th>
            <!--<th>Dias com Problema</th>-->
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="chamados in Chamados" :key="chamados.id_chamado">
            <td>{{ chamados.idfr_chamado }}</td>
            <td class="title">{{ chamados.titulo_chamado }}</td>
            <td>
              {{ chamados.prioridade_chamado_desc }}
              <i
                :class="getPriorityClass(chamados.prioridade_chamado_desc)"
                class="bi bi-circle-fill"
              ></i>
            </td>
            <td>{{ chamados.data_criacao_fm }}</td>
            <td>
              {{ chamados.status_chamado_desc }}
            </td>
            <td>
              {{ chamados.prazo }}
            </td>
            <td>{{ chamados.data_atualizacao_fm }}</td>
            <!--<td>{{ chamados.diasCProb }}</td>-->
            <td>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalVisualizarChamado"
                @click="verChamado(chamados)"
                title="Ver"
              >
                <i class="bi bi-eye"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalResponderChamado"
                @click="verChamado(chamados)"
                title="Responder chamado"
                v-if="chamados.status_chamado == 2"
              >
                <i class="bi bi-chat-right-dots green"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalHistoricoChamado"
                @click="onVisualizarHistoricoChamado(chamados)"
                title="Histórico do atendimento"
              >
                <i class="bi bi-card-list"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                data-bs-toggle="modal"
                data-bs-target="#modalEncaminharChamado"
                @click="verChamado(chamados)"
                title="Encaminhar"
                v-if="chamados.status_chamado == 2"
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
    <!----------------------Modal p/ visualizar informações do chamado---------------------->
    <div class="modal fade bd-example-modal-lg" id="modalVisualizarChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Dados do chamado</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
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
              <div class="form-group-modal">
                <label>Anexos</label>
                <input
                  type="file"
                  id="attachment"
                  ref="attachment"
                  name="anexo"
                  @change="handleFileUpload"
                  multiple
                  :disabled="!isEditing"
                />
                <table class="anexo-grid" v-if="anexos.length > 0">
                  <thead>
                    <tr>
                      <th>Arquivo</th>
                      <th>Tamanho</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(anexo, index) in anexos" :key="index">
                      <td>{{ anexo.name }}</td>
                      <td>{{ anexo.size }} bytes</td>
                      <td>
                        <button
                          class="bt-remove-anexo"
                          @click="removeAnexo(index)"
                          :disabled="!isEditing"
                        >
                          <i class="bi bi-x"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="confirmation-overlay" v-if="!isEditing">
                <div class="confirmation-box">
                  <button
                    type="submit"
                    class="btEditar"
                    @click="editarChamado"
                    v-if="
                      ChamadoData.status_chamado != 4 &&
                      ChamadoData.status_chamado != 0
                    "
                  >
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
        </div>
      </div>
    </div>
    <!----------------------Modal p/ visualizar informações do chamado---------------------->
    <!----------------------Modal p/ responder chamado---------------------->
    <div
      class="modal fade bd-example-modal-lg"
      id="modalResponderChamado"
    ></div>
    <!----------------------Modal p/ responder chamado---------------------->
    <!----------------------Modal p/ visualizar histórico do chamado---------------------->
    <div
      class="modal fade bd-example-modal-lg"
      id="modalHistoricoChamado"
    ></div>
    <!----------------------Modal p/ visualizar histórico do chamado---------------------->
    <!----------------------Modal p/ encaminhar chamado---------------------->
    <div
      class="modal fade bd-example-modal-lg"
      id="modalEncaminharChamado"
    ></div>
    <!----------------------Modal p/ encaminhar chamado---------------------->
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "MeusChamadosTec",
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
        id_user_tecnico: "",
        data_atualizacao: "",
        data_conclusao: "",
        //diasCProb: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_categoria_servico: "",
        id_categoria_ocorrencia: "",
        categoriaServico: "",
        categoriaOcorrencia: "",
        descricaoSolucao: "",
        observacao: "",
        novoTecnicoResponsavel: "",
        justificativaEncaminhamento: "",
      },
      Chamados: [],
      Historico: [],
      selectedStatus: null,
      tecnicos: [],
      isEditing: false,
      isConfirmingCancel: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      showResponderCampos: false,
      showEncaminharCampos: false,
      showBotoesAcao: true,
      categoriasServico: [],
      categoriasOcorrencia: [],
      showMessage: false,
      message: "",
      anexos: [], // Array para armazenar os arquivos anexados
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.fetchPrioridades();
    this.fetchCategoriasServico();
    this.fetchCategoriasOcorrencia();
    this.fetchUserTecnico();
  },
  methods: {
    onListarChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      console.log("ID de usuário: ", id_user);
      console.log("Permissao de usuario: ", permission);
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectChamados.php?action=selectChamados&id_user=${id_user}&permission=${permission}`
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.Chamados = res.data.chamados;
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
    fetchUserTecnico() {
      axios
        .get(
          "http://localhost/projeto/helptek/php/api/functions/getUserTecnico.php"
        )
        .then((response) => {
          this.tecnicos = response.data.tecnicos;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    verChamado(chamado) {
      this.ChamadoData = chamado;
      this.isEditing = false;
      this.ChamadoData.gravidade = chamado.gravidade;
      this.ChamadoData.urgencia = chamado.urgencia;
      this.ChamadoData.tendencia = chamado.tendencia;
    },
    responderChamado() {
      this.showResponderCampos = true;
      this.showEncaminharCampos = false;
      this.showBotoesAcao = false;
    },
    showAlert(message) {
      this.fecharModal();
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 3000); // Ajuste o tempo conforme necessário
    },
    closeMessage() {
      this.showMessage = false;
    },
    enviarResposta() {
      const {
        id_chamado,
        id_user_tecnico,
        idfr_chamado,
        categoriaServico,
        categoriaOcorrencia,
        descricaoSolucao,
      } = this.ChamadoData;
      console.log("Dados do chamado:", {
        id_chamado,
        id_user_tecnico,
        idfr_chamado,
        categoriaServico,
        categoriaOcorrencia,
        descricaoSolucao,
      });
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/responderChamado.php?action=ResponderChamado",
          {
            id_chamado,
            id_user_tecnico,
            idfr_chamado,
            categoriaServico,
            categoriaOcorrencia,
            descricaoSolucao,
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
      this.showResponderCampos = false;
    },
    encaminharChamado() {
      this.showResponderCampos = false;
      this.showEncaminharCampos = true;
      this.showBotoesAcao = false;
    },
    onEncaminharChamado() {
      const {
        id_chamado,
        idfr_chamado,
        id_user_tecnico,
        novoTecnicoResponsavel,
        justificativaEncaminhamento,
      } = this.ChamadoData;
      console.log("Dados do chamado:", {
        id_chamado,
        idfr_chamado,
        id_user_tecnico,
        novoTecnicoResponsavel,
        justificativaEncaminhamento,
      });
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/encaminharChamado.php?action=EncaminharChamado`,
          {
            id_chamado: id_chamado,
            idfr_chamado: idfr_chamado,
            id_user_tecnico: sessionStorage.getItem("id_user"),
            novoTecnicoResponsavel: novoTecnicoResponsavel,
            justificativaEncaminhamento: justificativaEncaminhamento,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error === true) {
            this.showAlert(res.data.msg);
          } else {
            this.showAlert(res.data.msg);
            this.onListarChamados(); // Atualiza a lista de chamados após assumir um chamado
          }
        })
        .catch((err) => {
          console.log(err);
        });
      this.showEncaminharCampos = false;
    },
    /*enviarChamado() {
      console.log("Novo tecnico responsavel:", this.novoTecnicoResponsavel);
      console.log(
        "Justifcativa encaminhamento:",
        this.justificativaEncaminhamento
      );

      this.showEncaminharCampos = false;
    },*/
    cancelar() {
      // Limpar os campos e retornar ao estado anterior do modal
      this.showResponderCampos = false;
      this.showEncaminharCampos = false;
      this.categoriaServico = "";
      this.categoriaOcorrencia = "";
      this.descricaoSolucao = "";
    },
    fecharModal() {
      this.showResponderCampos = false;
      this.showEncaminharCampos = false;
      this.showBotoesAcao = true; // Restaurar a visibilidade dos botões
      this.categoriaServico = "";
      this.categoriaOcorrencia = "";
      this.descricaoSolucao = "";
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
  },
};
</script>
