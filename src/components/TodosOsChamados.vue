<template>
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
      <table cellpadding="5" class="chamados-list-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titulo</th>
            <th>Prioridade</th>
            <th>Data de criação</th>
            <th>Status</th>
            <!--<th>Minutos de espera</th>-->
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
    <div
      class="modal fade"
      id="myModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header" style="width: 70%">
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
          <!-- Modal body -->
          <div class="modal-body">
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
              <div v-if="isTecnico">
                <button class="submit-button-modal" @click="assumirChamado">
                  Assumir Chamado
                </button>
              </div>
              <div v-if="isGerente">
                <button class="submit-button-modal" @click="encaminharChamado">
                  Encaminhar Chamado
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
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
        data_atualizacao: "",
        data_conclusao: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
      },
      Chamados: [],
      selectedStatus: null,
      isTecnico: false,
      isGerente: false,
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarChamados();
    this.checkPermissions();
    this.fetchPrioridades();
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
    assumirChamado() {
      const { id_chamado, idfr_chamado } = this.ChamadoData;
      console.log("Dados do chamado:", {
        idfr_chamado,
      });
      axios
        .post(
          `http://localhost/projeto/helptek/php/api/functions/assumirChamado.php`,
          {
            id_chamado: id_chamado,
            idfr_chamado: idfr_chamado,
            id_user_tecnico: sessionStorage.getItem("id_user"), // Supondo que o id do técnico está armazenado na sessão
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
  },
};
</script>
