<template>
    <div>
      <v-chart :option="chartOptions" style="width: 100%; height: 400px;"></v-chart>
    </div>
  </template>
  
  <script>
  import ECharts from 'vue-echarts'
  import 'echarts/lib/chart/pie'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/title'
  
  export default {
    name: 'PieChart',
    components: {
      'v-chart': ECharts
    },
    data() {
      return {
        chartOptions: {
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
        }
      }
    }
  }
  </script>
  
  <style scoped>
  /* Adicionar estilos */
  </style>