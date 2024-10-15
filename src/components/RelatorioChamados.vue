<template>
  <div class="ticket-form-container">
    <h1>Relatórios</h1>
    <div id="form-group-rel">
      <form @submit.prevent="onSelectRelatorio">
        <div id="form-rel-selector" class="form-group">
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
              Chamados por caregoria de ocorrência
            </option>
            <option value="dta_criacao">Chamados por data de criação</option>
            <option value="dta_conclusao">
              Chamados por data de conclusão
            </option>
          </select>
        </div>
      </form>
    </div>
    <div class="components-container">
      <component :is="getComponent"></component>
    </div>
  </div>
</template>
<script>
import ChamadosPorStatus from "@/components/cp_relatorio/ChamadosPorStatus.vue";
import ChamadosPorPrioridade from "@/components/cp_relatorio/ChamadosPorPrioridade.vue";
import ChamadosPorTecnico from "@/components/cp_relatorio/ChamadosPorTecnico.vue";
import ChamadosPorSetor from "@/components/cp_relatorio/ChamadosPorSetor.vue";
import ChamadosPorSolicitante from "@/components/cp_relatorio/ChamadosPorSolicitante.vue";
import ChamadosPorCatServico from "@/components/cp_relatorio/ChamadosPorCatServico.vue";
import ChamadosPorCatOcorrencia from "@/components/cp_relatorio/ChamadosPorCatOcorrencia.vue";
import ChamadosPorDtaCriacao from "@/components/cp_relatorio/ChamadosPorDtaCriacao.vue";
import ChamadosPorDtaConclusao from "@/components/cp_relatorio/ChamadosPorDtaConclusao.vue";

export default {
  created() {
    import("../assets/css/component/RelatorioCp.css");
  },
  data() {
    return {
      selectedConsulta: "", // Tipo de consulta selecionada
      showTypeRelatorio: true,
    };
  },
  computed: {
    getComponent() {
      switch (this.selectedConsulta) {
        case "status":
          return ChamadosPorStatus;
        case "prioridade":
          return ChamadosPorPrioridade;
        case "tecnico":
          return ChamadosPorTecnico;
        case "setor":
          return ChamadosPorSetor;
        case "solicitante":
          return ChamadosPorSolicitante;
        case "cat_serv":
          return ChamadosPorCatServico;
        case "cat_ocor":
          return ChamadosPorCatOcorrencia;
        case "dta_criacao":
          return ChamadosPorDtaCriacao;
        case "dta_conclusao":
          return ChamadosPorDtaConclusao;
        default:
          return null; // Nenhum componente é exibido se nada for selecionado
      }
    },
  },
  methods: {
    resetTypeRelatorio() {
      this.selectedConsulta = ""; // Limpa o select
      this.showTypeRelatorio = false;
    },
  },
};
</script>
