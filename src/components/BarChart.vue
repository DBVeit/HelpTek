<template>
    <div>
      <bar-chart :chartData="data" :options="options"></bar-chart>
    </div>
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs'
  
  export default {
    name: 'BarChart',
    extends: Bar,
    props: {
      chartData: {
        type: Object,
        required: true
      },
      options: {
        type: Object,
        required: false,
        default: () => {}
      }
    },
    mounted() {
      this.renderChart(this.chartData, this.options)
    }
  }
  </script>
  
  <style scoped>
  /* Adicionar estilos  */
  </style>