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
                data-bs-target="#modalDetalharChamado"
                @click="verChamado(chamados)"
                title="Detalhar chamado"
                v-if="chamados.status_chamado == 5"
              >
                <i class="bi bi-exclamation-triangle-fill yellow"></i>
              </button>
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
              @click="clearFormFields()"
            >
              &times;
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
                <span
                  class="form-tip"
                  v-if="!ChamadoData.titulo_chamado && showErrors"
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Descrição</label>
                <textarea
                  id="description"
                  rows="4"
                  v-model="ChamadoData.descricao_chamado"
                  :disabled="!isEditing"
                ></textarea>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.descricao_chamado && showErrors"
                  >*Preenchimento obrigatório!</span
                >
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
                <span
                  class="form-tip"
                  v-if="!ChamadoData.id_setor && showErrors"
                  >*Preenchimento obrigatório!</span
                >
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
                <span
                  class="form-tip"
                  v-if="!ChamadoData.gravidade && showErrors"
                  >*Preenchimento obrigatório!</span
                >
                <select v-model="ChamadoData.urgencia" :disabled="!isEditing">
                  <option
                    v-for="urgencia in prioridadesUrgencia"
                    :key="urgencia.id_prioridade"
                    :value="urgencia.valor_prioridade"
                  >
                    {{ urgencia.descricao_categoria }}
                  </option>
                </select>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.urgencia && showErrors"
                  >*Preenchimento obrigatório!</span
                >
                <select v-model="ChamadoData.tendencia" :disabled="!isEditing">
                  <option
                    v-for="tendencia in prioridadesTendencia"
                    :key="tendencia.id_prioridade"
                    :value="tendencia.valor_prioridade"
                  >
                    {{ tendencia.descricao_categoria }}
                  </option>
                </select>
                <span
                  class="form-tip"
                  v-if="!ChamadoData.tendencia && showErrors"
                  >*Preenchimento obrigatório!</span
                >
              </div>
              <div class="form-group-modal">
                <label>Anexos</label>
                <div v-if="isEditing">
                  <input
                    type="file"
                    id="attachment"
                    ref="attachment"
                    name="anexo"
                    @change="handleFileUpload"
                    multiple
                    :disabled="!isEditing"
                  />
                </div>
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
              <div class="confirmation-overlay" v-if="!isEditing">
                <div class="confirmation-box">
                  <button
                    type="submit"
                    class="submit-button"
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
              <div class="confirmation-box" v-else>
                <button
                  type="button"
                  class="submit-button"
                  @click="onSalvarEdicao"
                >
                  Salvar Alterações
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
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
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
                  <button
                    type="submit"
                    class="submit-button"
                    @click="editarChamado"
                  >
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
                <span
                  class="form-tip"
                  v-if="!ChamadoData.solicitacao_atendida && showErrors"
                  >*Selecionar uma opção!</span
                >
                <div class="form-group-modal">
                  <label>Observação *</label>
                  <textarea
                    name="observacao"
                    v-model="ChamadoData.observacao"
                    maxlength="255"
                  ></textarea>
                  <span
                    class="form-tip"
                    v-if="!ChamadoData.observacao && showErrors"
                    >*Preenchimento obrigatório!</span
                  >
                </div>
                <div class="confirmation-box">
                  <button
                    type="button"
                    class="submit-button"
                    @click="onAvaliarChamado"
                  >
                    Confirmar
                  </button>
                </div>
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
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalCancelarChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cancelar chamado</h4>
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
                    name="observacao_cancelamento"
                    v-model="ChamadoData.observacao_cancelamento"
                    maxlength="255"
                  ></textarea>
                  <span
                    class="form-tip"
                    v-if="!ChamadoData.observacao_cancelamento && showErrors"
                    >*Preenchimento obrigatório!</span
                  >
                </div>
                <div class="confirmation-box">
                  <button
                    type="button"
                    class="submit-button"
                    @click="onCancelarChamado"
                  >
                    Confirmar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------------------Modal p/ cancelar o chamado---------------------------------->
    <!----------------------------------Modal p/ detalhar o chamado---------------------------------->
    <div class="modal fade bd-example-modal-lg" id="modalDetalharChamado">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalhar chamado</h4>
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
            <div class="message-box" v-if="showMessage">
              <div class="message-content">
                <span>{{ message }}</span>
              </div>
            </div>
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
              <div>
                <h5>Detalhamento solicitado:</h5>
                <div class="form-group-modal">
                  <label>Observação</label>
                  <textarea
                    v-model="ChamadoData.observacao_detalhamento_tecnico"
                    disabled
                    onresize="false"
                  ></textarea>
                </div>
              </div>
            </div>
            <form method="POST" @submit.prevent="">
              <div>
                <h5>Resposta:</h5>
                <div class="form-group-modal">
                  <label>Observação *</label>
                  <textarea
                    name="observacao"
                    v-model="ChamadoData.observacao_detalhamento_solicitante"
                    maxlength="255"
                  ></textarea>
                  <span
                    class="form-tip"
                    v-if="
                      !ChamadoData.observacao_detalhamento_solicitante &&
                      showErrors
                    "
                    >*Preenchimento obrigatório!</span
                  >
                </div>
                <div class="confirmation-box">
                  <button
                    type="button"
                    class="submit-button"
                    @click="onDetalharChamado"
                  >
                    Responder
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!----------------------------------Modal p/ detalhar o chamado---------------------------------->
  </div>
</template>
<script>
import axios from "axios";
import { storage } from "@/firebase";
import { ref, uploadBytes, getDownloadURL } from "firebase/storage";

export default {
  name: "MeusChamados",
  data() {
    return {
      ChamadoData: {
        id_chamado: "",
        idfr_chamado: "",
        titulo_chamado: "",
        descricao_chamado: "",
        prioridade_chamado: "",
        data_criacao: "",
        status_chamado: "",
        data_atualizacao: "",
        data_conclusao: "",
        id_setor: "",
        peso: "",
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
        observacao_detalhamento_tecnico: "",
        observacao_detalhamento_solicitante: "",
        observacao_cancelamento: "",
      },
      Chamados: [],
      Historico: [],
      selectedStatus: "",
      isEditing: false,
      isConfirmingCancel: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      subsetores: [],
      categoriasServico: [],
      categoriasOcorrencia: [],
      recoverMessage: "",
      showMessage: false,
      message: "",
      showErrors: false,
      anexos: [], // Array para armazenar os arquivos anexados
      totalRegistros: 0,
      paginaAtual: 1,
      registrosPorPagina: 50,
      sessionUser: sessionStorage.getItem("id_user"),
      anexosUrls: [], // Array para armazenar as URLs dos arquivos no Firebase
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.fetchPrioridades();
    this.fetchCategoriasServico();
    this.fetchCategoriasOcorrencia();
    this.fetchSetoresList();
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
            this.totalRegistros = res.data.total;
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
            console.log(response.data.msg);
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
    async onSalvarEdicao() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.ChamadoData.titulo_chamado ||
        !this.ChamadoData.descricao_chamado ||
        !this.ChamadoData.id_setor ||
        !this.ChamadoData.gravidade ||
        !this.ChamadoData.urgencia ||
        !this.ChamadoData.tendencia
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("titulo_chamado", this.ChamadoData.titulo_chamado);
      data.append("descricao_chamado", this.ChamadoData.descricao_chamado);
      data.append("id_setor", this.ChamadoData.id_setor);
      data.append("peso", this.ChamadoData.peso);
      data.append("gravidade", this.ChamadoData.gravidade);
      data.append("urgencia", this.ChamadoData.urgencia);
      data.append("tendencia", this.ChamadoData.tendencia);
      data.append("id_user", id_user);

      // Primeiro faz o upload dos arquivos para o Firebase
      try {
        const uploadPromises = this.anexos.map((anexo) => {
          // Cria uma referência no Firebase Storage para o arquivo
          const storageRef = ref(storage, `anexos/${anexo.name}`);

          // Realiza o upload do arquivo e retorna a URL pública
          return uploadBytes(storageRef, anexo).then(async (snapshot) => {
            return await getDownloadURL(snapshot.ref); // Retorna a URL pública do arquivo
          });
        });

        // Aguarda o upload de todos os arquivos e captura as URLs
        this.anexosUrls = await Promise.all(uploadPromises);
      } catch (error) {
        console.error("Erro ao fazer upload dos anexos: ", error);
        return;
      }

      this.anexosUrls.forEach((url) => {
        data.append("anexosUrls[]", url);
      });

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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/s_editarChamado.php",
                data
              )
              .then((res_edita) => {
                console.log("Server response:", res_edita.data);
                if (res_edita.data.error === true) {
                  this.showAlert(res_edita.data.msg);
                } else {
                  this.showAlert(res_edita.data.msg);
                  this.closeModal("modalVisualizarEditarChamado");
                }
              })
              .catch((err) => {
                console.log(err);
              });
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
        titulo_chamado,
        descricao_chamado,
        id_setor,
        peso,
        gravidade,
        urgencia,
        tendencia,
      } = this.ChamadoData;
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/updateChamado.php?action=AtualizaChamado`,
          {
            id_chamado,
            id_user,
            idfr_chamado,
            titulo_chamado,
            descricao_chamado,
            id_setor,
            peso,
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
            this.closeModal("modalVisualizarEditarChamado");
          }
        })
        .catch((err) => {
          console.log(err);
        });*/
    },
    //Botão para liberar cancelamento do chamado
    confirmarCancelamento() {
      this.isConfirmingCancel = true;
    },
    //Função para cancelar um chamado
    onCancelarChamado() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (!this.ChamadoData.observacao_cancelamento) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user", id_user);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append(
        "observacao_cancelamento",
        this.ChamadoData.observacao_cancelamento
      );

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
                  this.showAlert(res_cancela.data.msg);
                  this.closeModal("modalCancelarChamado");
                }
              })
              .catch((err) => {
                console.log(err);
              });
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onAvaliarChamado() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.ChamadoData.solicitacao_atendida ||
        !this.ChamadoData.observacao
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user", id_user);
      data.append(
        "solicitacao_atendida",
        this.ChamadoData.solicitacao_atendida
      );
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
            // Cria um objeto para armazenar os dados
            let dataEntries = {};
            data.forEach((value, key) => {
              dataEntries[key] = value;
            });
            console.log(dataEntries); // Exibe o objeto com os dados
            axios
              .post(
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/s_avaliarChamado.php",
                data
              )
              .then((res_avalia) => {
                console.log("Server response:", res_avalia.data);
                if (res_avalia.data.error === true) {
                  this.showAlert(res_avalia.data.msg);
                } else {
                  this.showAlert(res_avalia.data.msg);
                  this.closeModal("modalAvaliarResposta");
                }
              })
              .catch((err) => {
                console.log(err);
              });
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    //Função para detalhar um chamado
    onDetalharChamado() {
      let data = new FormData();

      const id_user = sessionStorage.getItem("id_user");
      const session_token = localStorage.getItem("token");

      if (!id_user || !session_token) {
        this.showAlert("Usuário não autenticado. Faça login novamente.");
        return;
      }

      // Verifique se há algum campo obrigatório vazio
      if (!this.ChamadoData.observacao_detalhamento_solicitante) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      data.append("id_chamado", this.ChamadoData.id_chamado);
      data.append("id_user", id_user);
      data.append("idfr_chamado", this.ChamadoData.idfr_chamado);
      data.append(
        "observacao_detalhamento_solicitante",
        this.ChamadoData.observacao_detalhamento_solicitante
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
                "http://localhost/projeto/helptek/php/api/functions/chamados/update/s_detalharChamado.php",
                data
              )
              .then((res_detalhar) => {
                console.log("Server response:", res_detalhar.data);
                if (res_detalhar.data.error === true) {
                  this.showAlert(res_detalhar.data.msg);
                } else {
                  this.showAlert(res_detalhar.data.msg);
                  this.closeModal("modalDetalharChamado");
                }
              })
              .catch((err) => {
                console.log(err);
              });
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
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
      this.selectedStatus = "";
      this.onListarChamados();
      // Limpar a lista de chamados filtrados
    },
    //Exibir mensagem de retorno do back-end
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 6000);
    },
    //Limpar campos de preenchimento
    clearFormFields() {
      this.$refs.attachment.value = "";
      this.showErrors = false;
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
