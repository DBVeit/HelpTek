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
                <i class="bi bi-chat-right-dots"></i>
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
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-send"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"
                  />
                </svg>
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
              <div>
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
                <div>{{ ChamadoData.titulo_chamado }}</div>
              </div>
              <div class="form-group-modal">
                <label>Descrição</label>
                <div>{{ ChamadoData.descricao_chamado }}</div>
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
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------Modal p/ visualizar informações do chamado---------------------->
    <!----------------------Modal p/ responder chamado---------------------->
    <div class="modal fade bd-example-modal-lg" id="modalResponderChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Responder chamado</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" v-if="ChamadoData.observacao != ''">
            <div class="form-group-modal">
              <label>Observação</label>
              <textarea
                v-model="ChamadoData.observacao"
                disabled
                onresize="false"
              ></textarea>
            </div>
            <form method="POST">
              <div class="form-group-modal">
                <label>Categoria do Serviço</label>
                <select v-model="ChamadoData.categoriaServico">
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
                <select v-model="ChamadoData.categoriaOcorrencia">
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
                <textarea v-model="ChamadoData.descricaoSolucao"></textarea>
              </div>
              <div class="confirmation-overlay">
                <div class="confirmation-box">
                  <button class="submit-button-modal" @click="enviarResposta">
                    Enviar Resposta
                  </button>
                  <button class="submit-button-modal" @click="cancelar">
                    Cancelar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------Modal p/ responder chamado---------------------->
    <!----------------------Modal p/ visualizar histórico do chamado---------------------->
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
    <!----------------------Modal p/ visualizar histórico do chamado---------------------->
    <!----------------------Modal p/ encaminhar chamado---------------------->
    <div class="modal fade bd-example-modal-lg" id="modalEncaminharChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Encaminhar chamado</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST">
              <div class="form-group-modal">
                <label>Selecionar novo responsável</label>
                <select v-model="ChamadoData.novoTecnicoResponsavel">
                  <option value="" disabled>Selecionar...</option>
                  <option
                    v-for="tecnico in tecnicos"
                    :key="tecnico.id_user"
                    :value="tecnico.id_user"
                  >
                    {{ tecnico.idfr_code_user }} - {{ tecnico.name_user }} ({{
                      tecnico.id_user
                    }})
                  </option>
                </select>
              </div>
              <div class="form-group-modal">
                <label>Justificativa do encaminhamento*</label>
                <textarea
                  v-model="ChamadoData.justificativaEncaminhamento"
                ></textarea>
              </div>
              <div class="confirmation-overlay">
                <div class="confirmation-box">
                  <button
                    class="submit-button-modal"
                    @click="onEncaminharChamado"
                  >
                    Enviar
                  </button>
                  <button class="submit-button-modal" @click="cancelar">
                    Cancelar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
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
        usuario_chamado: "",
        tempo_espera: "",
        tecnico_responsavel: "",
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
      recoverMessage: "",
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
