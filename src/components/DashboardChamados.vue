<template>
  <div class="ticket-form-container">
    <h1>Dashboard</h1>
    <div class="filter">
      Filtros:
      <select>
        <option selected disabled>Selecionar...</option>
        <option value="1">Em aberto</option>
        <option value="2">Em atendimento</option>
        <option value="3">Respondido</option>
        <option value="4">Concluído</option>
        <option value="0">Cancelado</option>
      </select>
    </div>
    <div class="main-dash-area">
      <!-- Dropdown para selecionar o tipo de gráfico -->
      <div class="chart-options">
        <label for="chartType">Selecione o tipo de gráfico:</label>
        <select v-model="selectedChart" @change="renderChart">
          <option value="bar">Gráfico de Barras</option>
          <option value="pie">Gráfico de Pizza</option>
          <option value="line">Gráfico de Linha</option>
        </select>
      </div>

      <!-- Elemento para renderizar o gráfico -->
      <canvas id="chamadoChart" ref="chamadoChart"></canvas>
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
      chart: null, // Referência ao gráfico Chart.js
      chamadoData: {
        labels: [],
        datasets: [
          {
            label: "Chamados por Status",
            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
            data: [], // Exemplo de dados
          },
        ],
      },
    };
  },
  methods: {
    fetchChamadosData() {
      // Requisição ao back-end para obter os dados
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/dashboard/getChamadosData.php?action=getChamadosData`
        )
        .then((res) => {
          if (!res.data.error) {
            const chamados = res.data.data;

            // Atualizar labels e dados do gráfico
            this.chamadoData.labels = chamados.map(
              (item) => `Status ${item.status_chamado}`
            );
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
