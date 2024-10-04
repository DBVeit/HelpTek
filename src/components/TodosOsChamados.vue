<template>
  <div>
    <div class="ticket-form-container">
      <h1>Todos os chamados</h1>
      <div class="filter" v-if="isGerente">
        Filtros:
        <select v-model="selectedStatus" @change="filterChamados">
          <option selected disabled>Selecionar...</option>
          <option value="1">Em aberto</option>
          <option value="2">Em atendimento</option>
          <option value="3">Atendido</option>
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
              <th style="width: 50px">Titulo</th>
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
                  data-bs-target="#modalHistoricoChamado"
                  @click="onVisualizarHistoricoChamado(chamados)"
                  title="Histórico do atendimento"
                >
                  <i class="bi bi-card-list"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!----------------------Modal p/ visualizar informações do chamado---------------------->
      <div
        class="modal fade"
        id="modalVisualizarChamado"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Dados do chamado</h5>
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
              <form method="" @submit.prevent="">
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
                  <select v-model="ChamadoData.gravidade" disabled>
                    <option
                      v-for="gravidade in prioridadesGravidade"
                      :key="gravidade.id_prioridade"
                      :value="gravidade.valor_prioridade"
                    >
                      {{ gravidade.descricao_categoria }}
                    </option>
                  </select>
                  <select v-model="ChamadoData.urgencia" disabled>
                    <option
                      v-for="urgencia in prioridadesUrgencia"
                      :key="urgencia.id_prioridade"
                      :value="urgencia.valor_prioridade"
                    >
                      {{ urgencia.descricao_categoria }}
                    </option>
                  </select>
                  <select v-model="ChamadoData.tendencia" disabled>
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
                <!----------------------Assumir chamado (tecnico)---------------------->
                <div v-if="isTecnico && !confirmacaoAssumirChamado">
                  <button
                    class="submit-button-modal"
                    @click="exibirConfirmacao"
                  >
                    Assumir Chamado
                  </button>
                  <div
                    v-if="confirmacaoAssumirChamado"
                    class="confirmation-overlay"
                  >
                    <div class="confirmation-box">
                      <p>Deseja assumir este chamado?</p>
                      <button class="btAssumirSim" @click="assumirChamado">
                        Sim
                      </button>
                      <button
                        class="btAssumirNao"
                        @click="cancelarAssumirChamado"
                      >
                        Não
                      </button>
                    </div>
                  </div>
                </div>
                <!----------------------Encaminhar chamado (gerente)---------------------->
                <div v-if="isGerente">
                  <button class="submit-button-modal">
                    Encaminhar Chamado
                  </button>
                  <div class="form-group-modal">
                    <label>Selecionar novo responsável</label>
                    <select v-model="ChamadoData.novoTecnicoResponsavel">
                      <option value="" disabled>Selecionar...</option>
                      <option
                        v-for="tecnico in tecnicos"
                        :key="tecnico.id_user"
                        :value="tecnico.id_user"
                      >
                        {{ tecnico.idfr_code_user }} -
                        {{ tecnico.name_user }} ({{ tecnico.id_user }})
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
                      <button class="submit-button-modal">Enviar</button>
                      <button class="submit-button-modal">Cancelar</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!----------------------Modal p/ visualizar informações do chamado---------------------->
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
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "TodosOsChamados",
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
      isTecnico: false,
      isGerente: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      confirmacaoAssumirChamado: false,
      showEncaminharCampos: false,
      recoverMessage: "",
      showMessage: false,
      message: "",
      anexos: [], // Array para armazenar os arquivos anexados
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.checkPermissions();
    this.fetchPrioridades();
    this.fetchUserTecnico();
  },
  methods: {
    onListarChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      console.log(permission);
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectTodosChamados.php?action=selectTodosChamados&id_user=${id_user}&permission=${permission}`
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
      this.ChamadoData.gravidade = chamado.gravidade;
      this.ChamadoData.urgencia = chamado.urgencia;
      this.ChamadoData.tendencia = chamado.tendencia;
    },
    checkPermissions() {
      const permission = sessionStorage.getItem("permission");
      if (permission === "2") {
        this.isTecnico = true;
      } else if (permission === "3") {
        this.isGerente = true;
      }
    },
    exibirConfirmacao() {
      this.confirmacaoAssumirChamado = true;
    },
    cancelarAssumirChamado() {
      this.confirmacaoAssumirChamado = false;
    },
    assumirChamado() {
      const { id_chamado, idfr_chamado } = this.ChamadoData;
      console.log("Dados do chamado:", {
        idfr_chamado,
      });
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/assumirChamado.php?action=AssumirChamado`,
          {
            id_chamado: id_chamado,
            idfr_chamado: idfr_chamado,
            id_user_tecnico: sessionStorage.getItem("id_user"),
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error === true) {
            this.showAlert(res.data.msg);
            this.fecharModal();
          } else {
            this.showAlert(res.data.msg);
            this.onListarChamados();
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fecharModal() {
      const modal = document.getElementById("myModal");
      if (modal) {
        modal.classList.remove("show");
        modal.style.display = "none";
        const modalBackdrop = document.querySelector(".modal-backdrop");
        if (modalBackdrop) {
          modalBackdrop.remove();
        }
        document.body.classList.remove("modal-open");
      }
    },
    filterChamados() {
      const id_user = sessionStorage.getItem("id_user");
      if (!this.selectedStatus) return;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectTodosChamados.php?action=selectTodosChamados&id_user=${id_user}&status_chamado=${this.selectedStatus}`
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
  },
};
</script>
