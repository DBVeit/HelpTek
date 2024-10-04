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
                v-if="chamados.status_chamado == 3"
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
                data-bs-target="#modalCancelarChamado"
                @click="verChamado(chamados)"
                title="Cancelar"
                v-if="
                  chamados.status_chamado != 4 && chamados.status_chamado != 0
                "
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
          <div class="modal-header">
            <h4 class="modal-title">Dados do chamado</h4>
            <button
              type="button"
              data-bs-dismiss="modal"
              aria-label="Close"
              class="btn-close"
            >
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div style="width: 50%; float: left">
              <div>
                <span>
                  ID:
                  <h6 style="display: inline">
                    {{ ChamadoData.idfr_chamado }}
                  </h6>
                </span>
              </div>
              <div>
                <span>
                  Solicitante:
                  <h6 style="display: inline">
                    {{ ChamadoData.usuario_chamado }}
                  </h6>
                </span>
              </div>
              <div>
                <span>
                  Status:
                  <h6 style="display: inline">
                    {{ ChamadoData.status_chamado_desc }}
                  </h6>
                </span>
              </div>
              <div v-if="ChamadoData.tecnico_responsavel">
                <span>
                  Técnico:
                  <h6 style="display: inline">
                    {{ ChamadoData.tecnico_responsavel }}
                  </h6>
                </span>
              </div>
              <div>
                <span>
                  Última atualização:
                  <h6 style="display: inline">
                    {{ ChamadoData.data_atualizacao_fm }}
                  </h6>
                </span>
              </div>
            </div>
            <div style="float: right">
              <span>
                Tempo de espera:
                <h6>{{ ChamadoData.tempo_espera }}</h6>
              </span>
            </div>
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
    <!----------------------Modal p/ visualizar e editar informações do chamado---------------------->
    <!-----------------------Modal p/ visualizar e avaliar resposta do chamado----------------------->
    <div class="modal fade bd-example-modal-lg" id="modalAvaliarResposta">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Avaliar resposta</h4>
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
            <form method="POST" @submit.prevent="">
              <div
                class="form-group-modal confirmation-overlay"
                v-if="!isEditing"
              >
                <div class="confirmation-box">
                  <button type="submit" class="btEditar" @click="editarChamado">
                    Avaliar
                  </button>
                </div>
              </div>
              <div v-else>
                <h5 class="modal-title">Solicitação atendida?</h5>
                <div class="form-check form-check-inline">
                  <input
                    type="radio"
                    class="form-check-input"
                    name="solicitacao_atendida"
                    v-model="ChamadoData.solicitacao_atendida"
                    id="radio_sim"
                    value="1"
                  />
                  <label for="radio_sim" class="form-check-label"> Sim</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    type="radio"
                    class="form-check-input"
                    name="solicitacao_atendida"
                    v-model="ChamadoData.solicitacao_atendida"
                    id="radio_nao"
                    value="0"
                  />
                  <label for="radio_nao" class="form-check-label"> Não</label>
                </div>
                <div class="form-group-modal">
                  <label>Observação *</label>
                  <textarea
                    name="observacao"
                    v-model="ChamadoData.observacao"
                    maxlength="255"
                  ></textarea>
                </div>
                <button
                  type="button"
                  class="submit-button-modal"
                  @click="onAvaliarChamado"
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
    <!-----------------------Modal p/ visualizar histórico do chamado----------------------->
    <div class="modal fade bd-example-modal-lg" id="modalHistoricoChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Histórico do atendimento</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table
              class="historico-table"
              v-for="historico in Historico"
              :key="historico.id_acompanhamento"
            >
              <tr>
                <td rowspan="2" class="historico-info-i">
                  <i class="bi bi-person-circle"></i>
                </td>
                <td class="historico-info1">
                  {{ historico.data_acao_fm }} <br />
                  {{ historico.name_user }}
                </td>
              </tr>
              <tr>
                <td>
                  <label>Ação:</label> <label>{{ historico.acao }}</label>
                  <br />
                  <label> Descrição:</label>
                  <label> {{ historico.descricao_acao }}</label>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-----------------------Modal p/ visualizar histórico do chamado----------------------->
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalCancelarChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cancelar chamado</h4>
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
              <div>
                <h5 class="modal-title">
                  Deseja realmente cancelar o chamado
                  {{ ChamadoData.idfr_chamado }}?
                </h5>
                <div class="form-group-modal">
                  <label>Observação *</label>
                  <textarea
                    name="observacao"
                    v-model="ChamadoData.observacao"
                    maxlength="255"
                  ></textarea>
                </div>
                <button
                  type="button"
                  class="submit-button-modal"
                  @click="onCancelarChamado"
                >
                  Confirmar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
  </div>
</template>
<script>
import axios from "axios";

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
        //diasCProb: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_categoria_servico: "",
        id_categoria_ocorrencia: "",
        categoriaServico: "",
        categoriaOcorrencia: "",
        descricao_solucao: "",
        solicitacao_atendida: "",
        observacao: "",
        tempo_espera: "",
        tecnico_responsavel: "",
      },
      Chamados: [],
      Historico: [],
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
      showErrors: false,
      btAvaliar: false,
      anexos: [], // Array para armazenar os arquivos anexados
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
    //Listagem de chamados
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
    //Função para obter as prioridades
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
    //Função para obter as categorias de serviço
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
    //Função para obter as categorias de ocorrencia
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
    //Função para ver dados de um chamado da lista
    verChamado(chamado) {
      this.ChamadoData = chamado;
      this.isEditing = false;
      this.ChamadoData.gravidade = chamado.gravidade;
      this.ChamadoData.urgencia = chamado.urgencia;
      this.ChamadoData.tendencia = chamado.tendencia;

      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/chamados/read/getAnexosChamados.php?id_chamado=${chamado.id_chamado}`
        )
        .then((response) => {
          if (!response.data.error) {
            this.anexos = response.data.anexos;
          } else {
            console.error("Erro ao buscar anexos:", response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar anexos:", error);
        });
    },
    //Função para liberar edição do chamado
    editarChamado() {
      this.isEditing = true;
    },
    //Função para salvar dados editados do chamado
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
    //Botão para liberar cancelamento do chamado
    confirmarCancelamento() {
      this.isConfirmingCancel = true;
    },
    //Função para cancelar um chamado
    onCancelarChamado() {
      let data = new FormData();

      let id_user = sessionStorage.getItem("id_user");
      let session_token = localStorage.getItem("token");

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user", this.ChamadoData.id_user);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append("observacao", this.ChamadoData.observacao);

      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/session/checkUser.php?id_user=${id_user}&session_token=${session_token}`
        )
        .then((res) => {
          if (res.data.error === false) {
            console.log("Server response:", res.data.msg);
            const idfr_code_user = res.data.user;
            data.append("idfr_code_user", idfr_code_user);
            axios
              .post(
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/s_cancelarChamado.php",
                data
              )
              .then((res_cancela) => {
                console.log("Server response:", res_cancela.data);
                if (res_cancela.data.error === true) {
                  this.showAlert(res_cancela.data.msg);
                } else {
                  this.isConfirmingCancel = false;
                  this.showAlert(res_cancela.data.msg);
                  this.onListarChamados();
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
      /*.post(
          `http://localhost/projeto/helptek/php/api/functions/cancelaChamado.php?action=CancelaChamado`,
          {
            id_chamado,
            id_user,
            idfr_chamado,
            observacao,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.isConfirmingCancel = false;
          this.showAlert(res.data.msg);
          this.onListarChamados();
          //$("#myModal").modal("hide");
        })
        .catch((error) => {
          console.error("Erro ao cancelar chamado:", error);
        });*/
    },
    onAvaliarChamado() {
      this.showErrors = true;

      // Verifique se há algum campo obrigatório vazio
      /*if (
        !this.ChamadoData.solicitacao_atendida ||
        !this.ChamadoData.observacao
      ) {
        // Não prosseguir se houver erros
        return;
      }*/
      const {
        id_chamado,
        id_user = sessionStorage.getItem("id_user"),
        idfr_chamado,
        solicitacao_atendida,
        observacao,
      } = this.ChamadoData;
      console.log("Dados do chamado:", {
        id_chamado,
        id_user,
        idfr_chamado,
        solicitacao_atendida,
        observacao,
      });
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/avaliarAtendimento.php?action=AvaliarAtendimento`,
          {
            id_chamado,
            id_user,
            idfr_chamado,
            solicitacao_atendida,
            observacao,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.isConfirmingCancel = false;
          this.showAlert(res.data.msg);
          this.onListarChamados();
          //$("#myModal").modal("hide");
        })
        .catch((error) => {
          console.error("Erro ao avaliar chamado:", error);
        });
    },
    //Filtro simples de chamados
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
    //Função para limpar o filtro
    limparFiltros() {
      this.selectedStatus = null;
      this.onListarChamados();
      // Limpar a lista de chamados filtrados
    },
    //Exibir mensagem de retorno do back-end
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    //Limpar campos de preenchimento
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
    //Exibir botão para avaliar o atendimento
    showBtAvaliar(chamado) {
      this.ChamadoData = chamado;
      if (chamado.status_chamado == 3) {
        this.btAvaliar = true;
      }
    },
    //Exibir histórico do chamado
    onVisualizarHistoricoChamado(chamado) {
      this.ChamadoData = chamado;
      const id_chamado = this.ChamadoData.id_chamado;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/visualizarHistoricoChamado.php?action=selectHistorico&id_chamado=${id_chamado}`
        )
        .then((res) => {
          if (res.data.error === true) {
            console.log("Server response:", res.data.msg);
            this.recoverMessage = res.data.msg;
          } else {
            console.log("Server response:", res.data.historico);
            this.Historico = res.data.historico;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    handleFileUpload(event) {
      const files = event.target.files;
      for (let i = 0; i < files.length; i++) {
        this.anexos.push(files[i]);
      }
    },
    removeAnexo(index) {
      this.anexos.splice(index, 1); // Remove o anexo da lista
    },
    //Exibir cores diferentes de acordo com a prioridade
    getPriorityClass(prioridade) {
      switch (prioridade) {
        case "Baixa":
          return "green";
        case "Média":
          return "yellow";
        case "Alta":
          return "red";
        case "Crítica":
          return "black";
        default:
          return ""; // Classe vazia para evitar erros
      }
    },
  },
};
</script>
