<template>
    <div>
      <h1>Analytics</h1>
      <div class="chart-container">
        <BarChart :chartData="barChartData" :options="barChartOptions"/>
      </div>
      <div class="chart-container">
        <PieChart :chartOptions="pieChartOptions"/>
      </div>
      <div class="chart-container">
        <LineChart :chartData="lineChartData" :options="lineChartOptions"/>
      </div>
    </div>
  </template>
  
  <script>
  import BarChart from '@/components/BarChart.vue'
  import PieChart from '@/components/PieChart.vue'
  import LineChart from '@/components/LineChart.vue'
  
  export default {
    name: 'AnalyticsView',
    components: {
      BarChart,
      PieChart,
      LineChart
    },
    data() {
      return {
        barChartData: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          datasets: [
            {
              label: 'Chamados Criados',
              backgroundColor: '#f87979',
              data: [40, 20, 12, 39, 10, 40, 39]
            }
          ]
        },
        barChartOptions: {
          responsive: true,
          maintainAspectRatio: false
        },
        pieChartOptions: {
          title: {
            text: 'Distribuição de Chamados',
            left: 'center'
          },
          tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} ({d}%)'
          },
          series: [
            {
              name: 'Chamados',
              type: 'pie',
              radius: '55%',
              data: [
                { value: 1048, name: 'Abertos' },
                { value: 735, name: 'Em andamento' },
                { value: 580, name: 'Fechados' },
                { value: 484, name: 'Pendentes' },
                { value: 300, name: 'Cancelados' }
              ],
              emphasis: {
                itemStyle: {
                  shadowBlur: 10,
                  shadowOffsetX: 0,
                  shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
              }
            }
          ]
        },
        lineChartData: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          datasets: [
            {
              label: 'Chamados Resolvidos',
              backgroundColor: '#42A5F5',
              data: [30, 20, 15, 10, 30, 25, 60],
              fill: false
            }
          ]
        },
        lineChartOptions: {
          responsive: true,
          maintainAspectRatio: false
        }
      }
    },
    created() {
      const token = localStorage.getItem("token");
      const id_user = sessionStorage.getItem("id_user");
      if (!token || !id_user) {
        this.$router.push("/Login");
      } else {
        import("../assets/css/view/AnalyticsView.css")
          .then(() => {
            console.log("AnalyticsView style loaded");
          })
          .catch((err) => {
            console.error("AnalyticsView style load failed", err);
          });
      }
    }
  }
  </script>
  
  <style scoped>
  .chart-container {
    width: 100%;
    height: 400px;
    margin-bottom: 20px;
  }
  </style>