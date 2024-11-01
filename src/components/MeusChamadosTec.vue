<template>
  <div class="ticket-form-container">
    <h1>Meus chamados</h1>
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
        <input type="text" placeholder="Pesquisar ID ou título..." />
        <button class="bt-acoes-chamado">
          <i class="bi bi-search"></i>
        </button>
        <a href="" @click.prevent="limparFiltros" v-if="selectedStatus !== ''"
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
                data-bs-target="#modalSolicitarDetalhamento"
                @click="verChamado(chamados)"
                title="Solicitar Detalhamento"
                v-if="chamados.status_chamado == 2"
              >
                <i class="bi bi-question-circle"></i>
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
                <label>Setor</label>
                <select
                  name="setor"
                  id="setor"
                  v-model="ChamadoData.id_setor"
                  @change="updatePesoSetor"
                  :disabled="!isEditing"
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
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              @click="clearFormFields()"
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
            <div>
              <div v-if="ChamadoData.observacao_detalhamento_solicitante">
                <h5>Resposta ao detalhamento:</h5>
                <div class="form-group-modal">
                  <label>Observação</label>
                  <textarea
                    v-model="ChamadoData.observacao_detalhamento_solicitante"
                    disabled
                    onresize="false"
                  ></textarea>
                </div>
              </div>
              <div v-if="ChamadoData.solicitacao_atendida">
                <h5>Retorno da avaliação pelo solicitante:</h5>
                <div class="form-group-modal">
                  <span>Solicitação atendida?</span>
                  <b> {{ ChamadoData.solicitacao_atendida }}</b>
                  <label>Observação</label>
                  <textarea
                    v-model="ChamadoData.observacao"
                    disabled
                    onresize="false"
                  ></textarea>
                </div>
              </div>
            </div>
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
            <form method="POST" @submit.prevent="">
              <div class="form-group-modal">
                <label>Categoria do Serviço *</label>
                <select v-model="ChamadoData.id_categoria_servico">
                  <option default disabled="disabled" value>
                    Selecionar...
                  </option>
                  <option
                    v-for="categoriaServ in categoriasServico"
                    :key="categoriaServ.id_categoria_servico"
                    :value="categoriaServ.id_categoria_servico"
                  >
                    {{ categoriaServ.descricao_categoria_servico }}
                  </option>
                </select>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.id_categoria_servico && showErrors"
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Categoria da Ocorrência *</label>
                <select v-model="ChamadoData.id_categoria_ocorrencia">
                  <option default disabled="disabled" value>
                    Selecionar...
                  </option>
                  <option
                    v-for="categoriaOcor in categoriasOcorrencia"
                    :key="categoriaOcor.id_categoria_ocorrencia"
                    :value="categoriaOcor.id_categoria_ocorrencia"
                  >
                    {{ categoriaOcor.descricao_categoria_ocorrencia }}
                  </option>
                </select>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.id_categoria_ocorrencia && showErrors"
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Descrição da Solução *</label>
                <textarea v-model="ChamadoData.descricao_solucao"></textarea>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.descricao_solucao && showErrors"
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="confirmation-overlay">
                <div class="confirmation-box">
                  <button class="submit-button" @click="onEnviarResposta">
                    Enviar Resposta
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------Modal p/ responder chamado---------------------->
    <!----------------------Modal p/ solicitar detalhamento---------------------->
    <div class="modal fade bd-example-modal-lg" id="modalSolicitarDetalhamento">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Solicitar detalhamento</h4>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              @click="clearFormFields()"
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
              </div>
              <div class="right-info">
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
                    Técnico atual:
                    <h6 class="inline">
                      {{ ChamadoData.tecnico_responsavel }}
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
            <form method="POST" @submit.prevent="">
              <div class="form-group-modal">
                <label>Observação *</label>
                <textarea
                  v-model="ChamadoData.observacao_detalhamento_tecnico"
                ></textarea>
                <span
                  class="form-tip"
                  v-if="
                    !ChamadoData.observacao_detalhamento_tecnico && showErrors
                  "
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="confirmation-overlay">
                <div class="confirmation-box">
                  <button
                    class="submit-button"
                    @click="onSolicitarDetalhamento"
                  >
                    Enviar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------Modal p/ solicitar detalhamento---------------------->
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
                      <label v-if="historico.descricao_acao"> Descrição:</label>
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
    <!----------------------Modal p/ encaminhar chamado---------------------->
    <div class="modal fade bd-example-modal-lg" id="modalEncaminharChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Encaminhar chamado</h4>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              @click="clearFormFields()"
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
              </div>
              <div class="right-info">
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
                    Técnico atual:
                    <h6 class="inline">
                      {{ ChamadoData.tecnico_responsavel }}
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
            <form method="POST" @submit.prevent="">
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
                  v-if="!ChamadoData.justificativaEncaminhamento && showErrors"
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
        id_setor: "",
        peso: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_categoria_servico: "",
        id_categoria_ocorrencia: "",
        categoria_servico: "",
        categoria_ocorrencia: "",
        descricao_solucao: "",
        observacao: "",
        novoTecnicoResponsavel: "",
        justificativaEncaminhamento: "",
        usuario_chamado: "",
        tempo_espera: "",
        tecnico_responsavel: "",
        observacao_detalhamento_tecnico: "",
        observacao_detalhamento_solicitante: "",
        observacao_cancelamento: "",
        solicitacao_atendida: "",
      },
      Chamados: [],
      Historico: [],
      selectedStatus: "",
      tecnicos: [],
      isEditing: false,
      isConfirmingCancel: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      subsetores: [],
      showResponderCampos: false,
      showEncaminharCampos: false,
      showBotoesAcao: true,
      categoriasServico: [],
      categoriasOcorrencia: [],
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
    this.fetchPrioridades();
    this.fetchCategoriasServico();
    this.fetchCategoriasOcorrencia();
    this.fetchUserTecnico();
    this.fetchSetoresList();
  },
  methods: {
    //Listagem de chamados
    onListarChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      /*console.log("ID de usuário: ", id_user);
      console.log("Permissao de usuario: ", permission);*/
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectChamados.php?action=selectChamados&id_user=${id_user}&permission=${permission}`
        )
        .then((res) => {
          console.log("Server response:", res.data);
          this.Chamados = res.data.chamados;
          this.totalRegistros = res.data.total;
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
    // Função para carregar o setor e o peso ao abrir o modal
    updatePesoSetor() {
      // Busca o setor selecionado na lista de subsetores
      const setorSelecionado = this.subsetores.find(
        (subs) => subs.id_setor === this.ChamadoData.id_setor
      );

      // Se o setor for encontrado, atualiza o peso no ChamadoData
      if (setorSelecionado) {
        this.ChamadoData.peso = setorSelecionado.peso;
      } else {
        // Caso contrário, define o peso como 1 ou vazio
        this.ChamadoData.peso = 1;
      }
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
    //Função para ver dados de um chamado da lista
    verChamado(chamado) {
      this.ChamadoData = chamado;
      this.isEditing = false;
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
    responderChamado() {
      this.showResponderCampos = true;
      this.showEncaminharCampos = false;
      this.showBotoesAcao = false;
    },
    //Exibir mensagem de retorno do back-end
    showAlert(message) {
      this.fecharModal();
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 3000); // Ajuste o tempo conforme necessário
    },
    //Função para responder chamado
    onEnviarResposta() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.ChamadoData.id_categoria_servico ||
        !this.ChamadoData.id_categoria_ocorrencia ||
        !this.ChamadoData.descricao_solucao
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user_tecnico", this.ChamadoData.id_user_tecnico);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append(
        "id_categoria_servico",
        this.ChamadoData.id_categoria_servico
      );
      data.append(
        "id_categoria_ocorrencia",
        this.ChamadoData.id_categoria_ocorrencia
      );
      data.append("descricao_solucao", this.ChamadoData.descricao_solucao);
      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados

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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/t_responderChamado.php",
                data
              )
              .then((res_responde) => {
                console.log("Server response:", res_responde.data);
                if (res_responde.data.error === true) {
                  this.showAlert(res_responde.data.msg);
                } else {
                  this.showAlert(res_responde.data.msg);
                  this.closeModal("modalResponderChamado");
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
    //Função para exibir bt encaminhar
    encaminharChamado() {
      this.showResponderCampos = false;
      this.showEncaminharCampos = true;
      this.showBotoesAcao = false;
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
      data.append("id_user_tecnico", this.ChamadoData.id_user_tecnico);
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

      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados

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

      /*const {
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
      this.showEncaminharCampos = false;*/
    },
    /*enviarChamado() {
      console.log("Novo tecnico responsavel:", this.novoTecnicoResponsavel);
      console.log(
        "Justifcativa encaminhamento:",
        this.justificativaEncaminhamento
      );

      this.showEncaminharCampos = false;
    },*/
    //Função para encamninhar chamado
    onSolicitarDetalhamento() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (!this.ChamadoData.observacao_detalhamento_tecnico) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user_tecnico", this.ChamadoData.id_user_tecnico);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append(
        "observacao_detalhamento_tecnico",
        this.ChamadoData.observacao_detalhamento_tecnico
      );

      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados

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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/t_solicitarDetalhamento.php",
                data
              )
              .then((res_detalhamento) => {
                console.log("Server response:", res_detalhamento.data);
                if (res_detalhamento.data.error === true) {
                  this.showAlert(res_detalhamento.data.msg);
                } else {
                  this.showAlert(res_detalhamento.data.msg);
                  this.closeModal("modalSolicitarDetalhamento");
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
    cancelar() {
      // Limpar os campos e retornar ao estado anterior do modal
      this.showResponderCampos = false;
      this.showEncaminharCampos = false;
      this.categoria_servico = "";
      this.categoria_ocorrencia = "";
      this.descricao_solucao = "";
      this.novoTecnicoResponsavel = "";
      this.justificativaEncaminhamento = "";
    },
    fecharModal() {
      this.showResponderCampos = false;
      this.showEncaminharCampos = false;
      this.showBotoesAcao = true; // Restaurar a visibilidade dos botões
      this.categoria_servico = "";
      this.categoria_ocorrencia = "";
      this.descricao_solucao = "";
      this.novoTecnicoResponsavel = "";
      this.justificativaEncaminhamento = "";
    },
    //Filtro simples de chamados
    filterChamados() {
      const id_user = sessionStorage.getItem("id_user");
      const permission = sessionStorage.getItem("permission");
      if (!this.selectedStatus) return;
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/selectChamados.php?action=selectChamados&id_user=${id_user}&permission=${permission}&status_chamado=${this.selectedStatus}&prioridade_chamado=${this.selectedStatus}`
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
    //Limpar campos de preenchimento
    clearFormFields() {
      this.$refs.attachment.value = "";
      this.showErrors = false;
    },
  },
  computed: {
    ChamadosPaginados() {
      const inicio = (this.paginaAtual - 1) * this.ChamadosPorPagina;
      const fim = this.paginaAtual * this.ChamadosPorPagina;
      return this.Chamados.slice(inicio, fim);
    },
    totalPaginas() {
      return Math.ceil(this.Chamados.length / this.ChamadosPorPagina);
    },
  },
};
</script>
