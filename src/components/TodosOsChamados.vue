<template>
  <div>
    <div class="ticket-form-container">
      <h1>Todos os chamados</h1>
      <div class="filter">
        <div>
          <label for="filtro_status">Filtar por status: </label>
          <select v-model="selectedStatus" @change="filterChamados">
            <option default disabled="disabled" value>Selecionar...</option>
            <option value="1">Em aberto</option>
            <option value="2">Em atendimento</option>
            <option value="3">Respondido</option>
            <option value="4">Concluido</option>
            <option value="5">Detalhar chamado</option>
            <option value="0">Cancelado</option>
          </select>
          ou
          <input
            type="text"
            v-model="searchBy"
            @keyup="filterChamados"
            placeholder="Pesquisar ID ou título..."
          />
          <button class="bt-acoes-chamado">
            <i class="bi bi-search"></i>
          </button>
          <a
            href=""
            @click.prevent="limparFiltros"
            v-if="selectedStatus !== '' || searchBy !== ''"
            >Limpar filtros</a
          >
        </div>
      </div>
      <div>
        <div class="message-box" v-if="showMessage">
          <div class="message-content">
            <span>{{ message }}</span>
          </div>
        </div>
        <table class="chamados-list-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Titulo</th>
              <th>Prioridade</th>
              <th>Data de criação</th>
              <th>Status</th>
              <th>Prazo</th>
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
              <td class="td-txt-status">
                {{ chamados.status_chamado_desc }}
              </td>
              <td>
                {{ chamados.prazo }}
              </td>
              <td>{{ chamados.data_atualizacao_fm }}</td>
              <td class="td-bts-acoes">
                <button
                  class="bt-acoes-chamado"
                  data-bs-toggle="modal"
                  data-bs-target="#modalAssumirChamado"
                  @click="verChamado(chamados)"
                  title="Assumir chamado"
                  v-if="isTecnico"
                >
                  <i class="bi bi-clipboard-plus"></i>
                </button>
                <button
                  class="bt-acoes-chamado"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEncaminharChamado"
                  @click="verChamado(chamados)"
                  title="Encaminhar"
                  v-if="chamados.status_chamado == 1 && isGerente"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="12"
                    fill="currentColor"
                    class="bi bi-send"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"
                    />
                  </svg>
                </button>
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
        <div class="paginacao">
          <div class="pager">
            <!-- Tabela ou grid de Chamados -->
            <div v-for="Chamado in ChamadosPaginados" :key="Chamado.id">
              {{ Chamado.nome }}
            </div>
            <!-- Botões de paginação -->
            <div>
              <button
                class="bt-acoes-chamado"
                @click="paginaAtual--"
                :disabled="paginaAtual === 1"
              >
                <i class="bi bi-caret-left-fill"></i>
              </button>
              <button
                class="bt-acoes-chamado"
                @click="paginaAtual++"
                :disabled="paginaAtual === totalPaginas"
              >
                <i class="bi bi-caret-right-fill"></i>
              </button>
              <!--<span>Página {{ paginaAtual }} de {{ totalPaginas }}</span>-->
            </div>
          </div>
          <div class="totalizer">
            <span>Total de registros: {{ totalRegistros }}</span>
          </div>
        </div>
        <div class="recover-message" v-if="recoverMessage">
          {{ recoverMessage }}
        </div>
      </div>
      <!----------------------Modal p/ assumir chamado---------------------->
      <div
        class="modal fade bd-example-modal-lg"
        id="modalAssumirChamado"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="isTecnico"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Assumir chamado</h5>
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
              <div class="top_info">
                <div class="left_info">
                  <div>
                    <span>
                      ID:
                      <h6 class="inline">
                        {{ ChamadoData.idfr_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Solicitante:
                      <h6 class="inline">
                        {{ ChamadoData.usuario_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Status:
                      <h6 class="inline">
                        {{ ChamadoData.status_chamado_desc }}
                      </h6>
                    </span>
                  </div>
                  <div v-if="ChamadoData.tecnico_responsavel">
                    <span>
                      Técnico:
                      <h6 class="inline">
                        {{ ChamadoData.tecnico_responsavel }}
                      </h6>
                    </span>
                  </div>
                </div>
                <div class="right-info">
                  <div>
                    <span>
                      Tempo de espera:
                      <h6>{{ ChamadoData.tempo_espera }}</h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Última atualização:
                      <h6 class="inline">
                        {{ ChamadoData.data_atualizacao_fm }}
                      </h6>
                    </span>
                  </div>
                </div>
              </div>
              <br />
              <div class="message-box" v-if="showMessage">
                <div class="message-content">
                  <span>{{ message }}</span>
                </div>
              </div>
              <form method="" @submit.prevent="">
                <!----------------------Assumir chamado (tecnico)---------------------->
                <div>
                  <div class="confirmation-overlay">
                    <div class="confirmation-box">
                      <button class="submit-button" @click="onAssumirChamado">
                        Assumir Chamado
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!----------------------Modal p/ assumir chamado---------------------->
      <!----------------------Modal p/ encaminhar chamado---------------------->
      <div
        class="modal fade bd-example-modal-lg"
        id="modalEncaminharChamado"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="isGerente"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Encaminhar chamado</h5>
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
              <div class="top_info">
                <div class="left_info">
                  <div>
                    <span>
                      ID:
                      <h6 class="inline">
                        {{ ChamadoData.idfr_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Solicitante:
                      <h6 class="inline">
                        {{ ChamadoData.usuario_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Status:
                      <h6 class="inline">
                        {{ ChamadoData.status_chamado_desc }}
                      </h6>
                    </span>
                  </div>
                  <div v-if="ChamadoData.tecnico_responsavel">
                    <span>
                      Técnico:
                      <h6 class="inline">
                        {{ ChamadoData.tecnico_responsavel }}
                      </h6>
                    </span>
                  </div>
                </div>
                <div class="right-info">
                  <div>
                    <span>
                      Tempo de espera:
                      <h6>{{ ChamadoData.tempo_espera }}</h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Última atualização:
                      <h6 class="inline">
                        {{ ChamadoData.data_atualizacao_fm }}
                      </h6>
                    </span>
                  </div>
                </div>
              </div>
              <div class="message-box" v-if="showMessage">
                <div class="message-content">
                  <span>{{ message }}</span>
                </div>
              </div>
              <form method="" @submit.prevent="">
                <!----------------------Encaminhar chamado (gerente)---------------------->
                <div class="form-group-modal">
                  <label>Selecionar novo responsável *</label>
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
                  <span
                    class="form-tip"
                    v-if="!ChamadoData.novoTecnicoResponsavel && showErrors"
                    >*Preenchimento obrigatório!</span
                  >
                </div>
                <div class="form-group-modal">
                  <label>Justificativa do encaminhamento *</label>
                  <textarea
                    v-model="ChamadoData.justificativaEncaminhamento"
                  ></textarea>
                  <span
                    class="form-tip"
                    v-if="
                      !ChamadoData.justificativaEncaminhamento && showErrors
                    "
                    >*Preenchimento obrigatório!</span
                  >
                </div>
                <div class="confirmation-overlay">
                  <div class="confirmation-box">
                    <button class="submit-button" @click="onEncaminharChamado">
                      Enviar
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!----------------------Modal p/ assumir chamado---------------------->
      <!----------------------Modal p/ visualizar informações do chamado---------------------->
      <div
        class="modal fade bd-example-modal-lg"
        id="modalVisualizarChamado"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg" role="document">
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
              <div class="top_info">
                <div class="left_info">
                  <div>
                    <span>
                      ID:
                      <h6 class="inline">
                        {{ ChamadoData.idfr_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Solicitante:
                      <h6 class="inline">
                        {{ ChamadoData.usuario_chamado }}
                      </h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Status:
                      <h6 class="inline">
                        {{ ChamadoData.status_chamado_desc }}
                      </h6>
                    </span>
                  </div>
                  <div v-if="ChamadoData.tecnico_responsavel">
                    <span>
                      Técnico:
                      <h6 class="inline">
                        {{ ChamadoData.tecnico_responsavel }}
                      </h6>
                    </span>
                  </div>
                </div>
                <div class="right-info">
                  <div>
                    <span>
                      Tempo de espera:
                      <h6>{{ ChamadoData.tempo_espera }}</h6>
                    </span>
                  </div>
                  <div>
                    <span>
                      Última atualização:
                      <h6 class="inline">
                        {{ ChamadoData.data_atualizacao_fm }}
                      </h6>
                    </span>
                  </div>
                </div>
              </div>
              <div class="message-box" v-if="showMessage">
                <div class="message-content">
                  <span>{{ message }}</span>
                </div>
              </div>
              <form method="" @submit.prevent="">
                <div class="form-group-modal">
                  <label>Título</label>
                  <input
                    type="text"
                    id="subject"
                    v-model="ChamadoData.titulo_chamado"
                    disabled
                  />
                </div>
                <div class="form-group-modal">
                  <label>Descrição</label>
                  <textarea
                    id="description"
                    rows="4"
                    v-model="ChamadoData.descricao_chamado"
                    disabled
                  ></textarea>
                </div>
                <div class="form-group-modal">
                  <label>Setor</label>
                  <select
                    name="setor"
                    id="setor"
                    v-model="ChamadoData.id_setor"
                    disabled
                  >
                    <option default value="" disabled>Setor</option>
                    <option
                      v-for="subs in subsetores"
                      :key="subs.id_setor"
                      :value="subs.id_setor"
                    >
                      {{ subs.nome_setor + " (IPS: " + subs.peso + ")" }}
                    </option>
                  </select>
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
                  <div class="confirmation-box">
                    <table class="anexo-grid" v-if="anexos.length > 0">
                      <thead>
                        <tr>
                          <th>Anexos</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(anexo, index) in anexos" :key="index">
                          <td>
                            <a :href="anexo.caminho_arquivo" target="_blank"
                              >Visualizar anexo</a
                            >
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!----------------------Assumir chamado (tecnico)---------------------
                <div v-if="isTecnico">
                  <div class="confirmation-overlay">
                    <div class="confirmation-box">
                      <button class="submit-button" @click="onAssumirChamado">
                        Assumir Chamado
                      </button>
                    </div>
                  </div>
                </div>-->
                <!----------------------Encaminhar chamado (gerente)--------------------
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
                </div>-->
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
              <div
                v-for="historico in Historico"
                :key="historico.id_acompanhamento"
              >
                <div
                  :class="
                    historico.id_usuario_acao === sessionUser
                      ? 'msg_send'
                      : 'msg_receive'
                  "
                >
                  <table class="historico-table">
                    <tr>
                      <td class="historico-info1">
                        <b>{{ historico.data_acao_fm }}</b> <br />
                        <b>{{ historico.name_user }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Ação:</label> <label>{{ historico.acao }}</label>
                        <br />
                        <label v-if="historico.descricao_acao">
                          Descrição:</label
                        >
                        <label v-if="historico.descricao_acao">
                          {{ historico.descricao_acao }}</label
                        >
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
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
        id_setor: "",
        peso: "",
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
      selectedStatus: "",
      searchBy: "",
      tecnicos: [],
      isTecnico: false,
      isGerente: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      subsetores: [],
      confirmacaoAssumirChamado: false,
      showEncaminharCampos: false,
      recoverMessage: "",
      showMessage: false,
      showErrors: false,
      message: "",
      anexos: [], // Array para armazenar os arquivos anexados
      totalRegistros: 0,
      paginaAtual: 1,
      registrosPorPagina: 50,
      sessionUser: sessionStorage.getItem("id_user"),
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.checkPermissions();
    this.fetchPrioridades();
    this.fetchUserTecnico();
    this.fetchSetoresList();
  },
  methods: {
    onListarChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectTodosChamados.php?action=selectTodosChamados&id_user=${id_user}&permission=${permission}`
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.Chamados = res.data.chamados;
          this.totalRegistros = res.data.total;
          this.totalRegistros = this.Chamados.length;
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
    // Função para obter a lista de usuários tecnico
    fetchUserTecnico() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/getUserTecnico.php?id_user_session=${id_user}&permission_user_session=${permission}`
        )
        .then((response) => {
          this.tecnicos = response.data.tecnicos;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    //Listagem de setores
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
    //Função para ver dados de um chamado da lista
    verChamado(chamado) {
      this.ChamadoData = chamado;
      this.ChamadoData.gravidade = chamado.gravidade;
      this.ChamadoData.urgencia = chamado.urgencia;
      this.ChamadoData.tendencia = chamado.tendencia;
      this.showErrors = false;
      this.ChamadoData.novoTecnicoResponsavel = "";
      this.ChamadoData.justificativaEncaminhamento = "";
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/chamados/read/getAnexosChamados.php?id_chamado=${chamado.id_chamado}`
        )
        .then((response) => {
          if (!response.data.error) {
            this.anexos = response.data.anexos;
          } else {
            console.log(response.data.msg);
          }
        })
        .catch((error) => {
          console.error("Erro ao buscar anexos:", error);
        });
    },
    //Verificar tipo de permissão do usuário logado
    checkPermissions() {
      const permission = sessionStorage.getItem("permission");
      if (permission === "2") {
        this.isTecnico = true;
      } else if (permission === "3") {
        this.isGerente = true;
      }
    },
    //Função para assumir chamado como técnico
    onAssumirChamado() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user_tecnico", id_user);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);

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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/t_assumirChamado.php",
                data
              )
              .then((res_assume) => {
                console.log("Server response:", res_assume.data);
                if (res_assume.data.error === true) {
                  this.showAlert(res_assume.data.msg);
                  this.closeModal("modalAssumirChamado");
                } else {
                  this.showAlert(res_assume.data.msg);
                  this.closeModal("modalAssumirChamado");
                }
              })
              .catch((err) => {
                console.log(err);
              });
            //this.showResponderCampos = false;
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    //Função para encamninhar chamado
    onEncaminharChamado() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");
      const permission = sessionStorage.getItem("permission");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.ChamadoData.novoTecnicoResponsavel ||
        !this.ChamadoData.justificativaEncaminhamento
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user_tecnico", id_user);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append(
        "novoTecnicoResponsavel",
        this.ChamadoData.novoTecnicoResponsavel
      );
      data.append(
        "justificativaEncaminhamento",
        this.ChamadoData.justificativaEncaminhamento
      );
      data.append("permission", permission);

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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/t_g_encaminharChamado.php",
                data
              )
              .then((res_encaminha) => {
                console.log("Server response:", res_encaminha.data);
                if (res_encaminha.data.error === true) {
                  this.showAlert(res_encaminha.data.msg);
                } else {
                  this.showAlert(res_encaminha.data.msg);
                  this.closeModal("modalEncaminharChamado");
                }
              })
              .catch((err) => {
                console.log(err);
              });
            this.showResponderCampos = false;
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
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
      this.selectedStatus = "";
      this.searchBy = "";
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
    // Código para fechar o modal
    closeModal(modalId) {
      // Seleciona o botão de fechar dentro do modal
      const closeButton = document.querySelector(`#${modalId} .btn-close`);
      // Simula o clique no botão de fechar para fechar o modal
      if (closeButton) {
        closeButton.click();
      }
      this.onListarChamados();
    },
  },
  computed: {
    ChamadosPaginados() {
      const inicio = (this.paginaAtual - 1) * this.registrosPorPagina;
      const fim = this.paginaAtual * this.registrosPorPagina;
      return this.Chamados.slice(inicio, fim);
    },
    totalPaginas() {
      return Math.ceil(this.Chamados.length / this.registrosPorPagina);
    },
  },
};
</script>
