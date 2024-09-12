<template>
  <div class="ticket-form-container">
    <h1>Dashboard</h1>
    <div>
      <form @submit.prevent="onSelectConsultaDashboard">
        <div class="form-group">
          <label for="chartType">Selecione o tipo de consulta:</label>
          <select v-model="selectedConsulta" class="form-select" required>
            <option value="" disabled selected>Selecionar...</option>
            <option value="status">Chamados por status</option>
            <option value="prioridade">Chamados por prioridade</option>
            <option value="tecnico">Chamados por técnico</option>
            <option value="periodo">Chamados por período</option>
            <option value="setor">Chamados por setor</option>
            <option value="usuario">Chamados por usuário</option>
          </select>
        </div>
        <div class="form-group">
          <button class="submit-button-chamado">Executar</button>
        </div>
      </form>
    </div>
    <div v-if="showDashboard" class="main-dash-area">
      <!-- Dropdown para selecionar o tipo de gráfico -->
      <div class="chart-options">
        <select
          class="form-select"
          v-model="selectedChart"
          @change="renderChart"
        >
          <option value="bar">Gráfico de Barras</option>
          <option value="pie">Gráfico de Pizza</option>
          <option value="line">Gráfico de Linha</option>
        </select>
      </div>

      <!-- Elemento para renderizar o gráfico -->
      <div class="chart-area">
        <canvas id="chamadoChart" ref="chamadoChart"></canvas>
      </div>
    </div>
  </div>
</template>
<script>
import { Chart, registerables } from "chart.js";
import axios from "axios";
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

            // Atualizar labels e dados do gráfico dinamicamente
            this.chamadoData.labels = chamados.map((item) => item.label);
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
    renderChart() {
      // Destruir gráfico anterior ao trocar o tipo
      if (this.chart) {
        this.chart.destroy();
      }

      // Criar o novo gráfico com o tipo selecionado
      this.chart = new Chart(this.$refs.chamadoChart, {
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
    },
    onSelectConsultaDashboard() {
      // Chamar a função de busca de dados com o tipo de consulta selecionado
      this.fetchChamadosData(this.selectedConsulta);
      this.showDashboard = true; // Exibe a div com o gráfico após o submit
    },
  },
  mounted() {
    this.fetchChamadosData(); // Renderizar gráfico ao carregar a página
  },
};
</script>

<style scoped>
.chart-options {
  margin-bottom: 20px;
}
</style>
