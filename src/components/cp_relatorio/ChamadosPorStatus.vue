<template>
  <div class="component-main-container">
    <form @submit.prevent="onSelectStatusRel">
      <div class="form-group">
        <label>Status: </label>
        <select>
          <option selected disabled>Selecionar...</option>
          <option value="">Todos</option>
          <option value="1">Em aberto</option>
          <option value="2">Em atendimento</option>
          <option value="3">Respondido</option>
          <option value="4">Concluido</option>
          <option value="0">Cancelado</option>
        </select>
      </div>
      <div class="form-group">
        <label>Data de criação: </label>
        <input type="date" />
        Até
        <input type="date" />
      </div>
      <div id="form-dash-submit" class="form-group-dashboard">
        <button class="submit-button-chamado">Executar</button>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "ChamadosPorStatus",
  data() {
    return {
      selectedStatus: null,
    };
  },
  methods: {
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
  },
};
</script>
