<template>
  <div class="ticket-form-container">
    <h1>Dashboard</h1>
    <div id="form-group-dashboard">
      <form @submit.prevent="onSelectConsultaDashboard">
        <div id="form-dash-selector" class="form-group">
          <label for="chartType">Selecione o tipo de consulta:</label>
          <select v-model="selectedConsulta" class="form-select" required>
            <option value="" disabled selected>Selecionar...</option>
            <option value="status">Chamados por status</option>
            <option value="prioridade">Chamados por prioridade</option>
            <option value="tecnico">Chamados por usuário técnico</option>
            <option value="setor">Chamados por setor</option>
            <option value="solicitante">
              Chamados por usuário solicitante
            </option>
            <option value="cat_serv">Chamados por categoria de serviço</option>
            <option value="cat_ocor">
              Chamados por categoria de ocorrência
            </option>
            <option value="dta_abr">Chamados por data de abertura</option>
            <option value="dta_conc">Chamados por data de conclusão</option>
          </select>
          <a
            v-if="showDashboard"
            @click.prevent="resetDashboard"
            class="clear-selection-link"
            >Limpar seleção</a
          >
        </div>
        <div id="form-dash-submit" class="form-group-dashboard">
          <button class="submit-button-chamado">Executar</button>
        </div>
      </form>
    </div>
    <div v-if="showDashboard" class="main-dash-area">
      <div class="chart-options">
        <select
          class="form-select"
          v-model="selectedChart"
          @change="renderChart(false)"
        >
          <option value="bar">Gráfico de Barras</option>
          <option value="pie">Gráfico de Pizza</option>
          <option value="line">Gráfico de Linha</option>
        </select>
      </div>
      <div class="chart-area">
        <button class="bt-acoes-dash" @click="openModal" title="Expandir">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>
        <canvas id="chamadoChart" ref="chamadoChart"></canvas>
      </div>
    </div>

    <!----------Gráfico expandido---------->
    <div
      class="modal fade"
      id="chartModal"
      tabindex="-1"
      aria-labelledby="chartModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="chart-options">
              <select
                class="form-select"
                v-model="selectedChart"
                @change="renderChart(true)"
              >
                <option value="bar">Gráfico de Barras</option>
                <option value="pie">Gráfico de Pizza</option>
                <option value="line">Gráfico de Linha</option>
              </select>
            </div>
            <div class="chart-area">
              <canvas id="modalChart" ref="modalChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!----------Gráfico expandido---------->
  </div>
</template>
<script>
import { Chart, registerables } from "chart.js";
import axios from "axios";
import { Modal } from "bootstrap";
Chart.register(...registerables);

export default {
  created() {
    import("../assets/css/component/DashboardCp.css");
  },
  data() {
    return {
      selectedChart: "bar", // Tipo de gráfico selecionado
      selectedConsulta: "", // Tipo de consulta selecionada
      showDashboard: false, // Controla a exibição da div main-dash-area
      chart: null, // Referência ao gráfico Chart.js
      modalChart: null, // Referência ao gráfico Chart.js no modal
      chamadoData: {
        labels: [],
        datasets: [
          {
            label: "Dados do chamado",
            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
            data: [], // Exemplo de dados
          },
        ],
      },
    };
  },
  methods: {
    fetchChamadosData(consulta) {
      // Requisição ao back-end para obter os dados
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/dashboard/getChamadosData.php?action=getChamadosData&consulta=${consulta}`
        )
        .then((res) => {
          if (!res.data.error) {
            const chamados = res.data.data;
            console.log(res.data.data);

            if (consulta === "status") {
              // Mapear os status para suas descrições
              const statusMap = {
                0: "Cancelado",
                1: "Em aberto",
                2: "Em atendimento",
                3: "Respondido",
                4: "Concluído",
              };

              // Atualizar labels e dados do gráfico com as descrições
              this.chamadoData.labels = chamados.map(
                (item) => statusMap[item.status_chamado] || "Desconhecido"
              );
              console.log(statusMap);
            } else if (consulta === "prioridade") {
              // Atualizar labels com base na prioridade descrita no back-end
              this.chamadoData.labels = chamados.map(
                (item) => item.prioridade_chamado || "Desconhecido"
              );
            } else if (consulta === "tecnico") {
              // Mapa de técnicos (aqui você pode mapear ids para nomes, se necessário)
              this.chamadoData.labels = chamados.map(
                (item) => `Técnico ${item.id_user_tecnico}`
              );
            } else {
              // Para outros tipos de consulta, use o campo 'label'
              this.chamadoData.labels = chamados.map((item) => item.label);
            }

            // Atualizar os dados do gráfico
            this.chamadoData.datasets[0].data = chamados.map(
              (item) => item.total
            );

            // Renderizar o gráfico com os novos dados
            this.renderChart();
          } else {
            console.error("Erro ao buscar dados dos chamados:", res.data.msg);
          }
        })
        .catch((err) => {
          console.error("Erro na requisição ao servidor:", err);
        });
    },
    renderChart(isModal = false) {
      const chartRef = isModal
        ? this.$refs.modalChart
        : this.$refs.chamadoChart;

      // Destruir gráfico anterior ao trocar o tipo
      if (isModal && this.modalChart) {
        this.modalChart.destroy();
      } else if (!isModal && this.chart) {
        this.chart.destroy();
      }

      // Criar o novo gráfico com o tipo selecionado
      const newChart = new Chart(chartRef, {
        type: this.selectedChart, // Tipo de gráfico dinâmico
        data: this.chamadoData, // Dados do gráfico
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: true,
              text: "Relatório de Chamados",
            },
          },
        },
      });
      if (isModal) {
        this.modalChart = newChart;
      } else {
        this.chart = newChart;
      }
    },
    onSelectConsultaDashboard() {
      // Chamar a função de busca de dados com o tipo de consulta selecionado
      this.fetchChamadosData(this.selectedConsulta);
      this.showDashboard = true; // Exibe a div com o gráfico após o submit
    },
    // Função para limpar a seleção e ocultar o gráfico
    resetDashboard() {
      this.selectedConsulta = ""; // Limpa o select
      this.showDashboard = false; // Oculta a div do dashboard
      if (this.chart) {
        this.chart.destroy(); // Destroi o gráfico se ele existir
      }
    },
    // Função para abrir o modal com o gráfico expandido
    openModal() {
      const modalElement = document.getElementById("chartModal");
      const modalInstance = new Modal(modalElement);
      modalInstance.show();

      // Renderizar o gráfico dentro do modal
      this.renderChart(true);
    },
  },
  mounted() {
    this.fetchChamadosData(); // Renderizar gráfico ao carregar a página
  },
};
</script>
