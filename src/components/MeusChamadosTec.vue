<template>
  <div>
    <div class="message-box" v-if="showMessage">
      <div class="message-content">
        <span>{{ message }}</span>
        <button @click="closeMessage">X</button>
      </div>
    </div>
    <div class="page_title">
      <h1>Meus chamados</h1>
    </div>
    <div class="">
      <div class="filter">
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
              <th>Titulo</th>
              <th>Prioridade</th>
              <th>Data de criação</th>
              <th>Status</th>
              <!--<th>Minutos de espera</th>-->
              <th>Atualizado em</th>
              <th>Concluído em</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="chamados in Chamados" :key="chamados.id_chamado">
              <td>{{ chamados.idfr_chamado }}</td>
              <td class="title">{{ chamados.titulo_chamado }}</td>
              <td>{{ chamados.prioridade_chamado }}</td>
              <td>{{ chamados.data_criacao_fm }}</td>
              <td>{{ chamados.status_chamado_desc }}</td>
              <!--<td>{{ chamados.minutos_espera }}</td>-->
              <td>{{ chamados.data_atualizacao_fm }}</td>
              <td>{{ chamados.data_conclusao_fm }}</td>
              <td>
                <button
                  class="bt-chamado"
                  data-bs-toggle="modal"
                  data-bs-target="#myModal"
                  @click="verChamado(chamados)"
                >
                  Ver
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- The Modal -->
      <div class="modal fade bd-example-modal-lg" id="myModal">
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
                @click="fecharModal"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form method="POST" @submit.prevent="">
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
                <div>
                  <label>Anexos</label>
                </div>
                <!--Botoes de ação-->
                <div v-if="!showResponderCampos && !showEncaminharCampos">
                  <button class="submit-button-modal" @click="responderChamado">
                    Responder Chamado
                  </button>
                  <button
                    class="submit-button-modal"
                    @click="encaminharChamado"
                  >
                    Encaminhar Chamado
                  </button>
                </div>
                <!--Campos responder chamado-->
                <div v-if="showResponderCampos">
                  <h4 class="modal-title">Responder Chamado</h4>
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
                  <button class="submit-button-modal" @click="enviarResposta">
                    Enviar Resposta
                  </button>
                  <button class="submit-button-modal" @click="cancelar">
                    Cancelar
                  </button>
                </div>
                <!--Campos encaminhar chamado-->
                <div
                  v-if="showEncaminharCampos && ChamadoData.status_chamado != 4"
                >
                  <h4 class="modal-title">Encaminhar Chamado</h4>
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
                    <label>Justificativa do encaminhamento</label>
                    <textarea
                      v-model="ChamadoData.justificativaEncaminhamento"
                    ></textarea>
                  </div>
                  <button class="submit-button-modal" @click="enviarChamado">
                    Enviar
                  </button>
                  <button class="submit-button-modal" @click="cancelar">
                    Cancelar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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
        gravidade: "",
        urgencia: "",
        tendencia: "",
        categoriaServico: "",
        categoriaOcorrencia: "",
        descricaoSolucao: "",
        novoTecnicoResponsavel: "",
        justificativaEncaminhamento: "",
      },
      Chamados: [],
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
    showAlert(message) {
      this.fecharModal();
      this.limparCamposModal();
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 3000); // Ajuste o tempo conforme necessário
    },
    closeMessage() {
      this.showMessage = false;
    },
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
    enviarResposta() {
      const {
        id_chamado,
        id_user,
        id_user_tecnico,
        idfr_chamado,
        categoriaServico,
        categoriaOcorrencia,
        descricaoSolucao,
      } = this.ChamadoData;
      console.log("Dados do chamado:", {
        id_chamado,
        id_user,
        id_user_tecnico,
        idfr_chamado,
        categoriaServico,
        categoriaOcorrencia,
        descricaoSolucao,
      });
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/responderChamado.php",
          {
            id_chamado,
            id_user,
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
    enviarChamado() {
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
          `http://localhost/projeto/helptek/php/api/functions/encaminharChamado.php`,
          {
            id_chamado: id_chamado,
            idfr_chamado: idfr_chamado,
            id_user_tecnico: sessionStorage.getItem("id_user"), // Supondo que o id do técnico está armazenado na sessão
            novoTecnicoResponsavel: novoTecnicoResponsavel,
            justificativaEncaminhamento: justificativaEncaminhamento,
          }
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error === true) {
            alert(res.data.msg);
          } else {
            alert(res.data.msg);
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
