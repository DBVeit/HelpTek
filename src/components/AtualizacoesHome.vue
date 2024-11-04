<template>
  <div class="ticket-form-container">
    <h1>Atualizações</h1>
    <div>
      <table
        class="atualizacoes-list-table"
        v-if="id_permissao !== 3 && id_permissao !== 4"
      >
        <thead>
          <tr>
            <th>Atualizações desde seu último login</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="Chamados.length === 0">
            <td>{{ atualizacoesMsg }}</td>
          </tr>
          <tr v-else v-for="chamados in Chamados" :key="chamados.id_chamado">
            <td>{{ chamados.mensagem }}</td>
          </tr>
        </tbody>
      </table>
      <table class="atualizacoes-list-table">
        <thead>
          <tr>
            <th>Nas últimas 24h...</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              {{ estatisticas.chamados24h }} novos chamados; Total de chamados
              registrados: {{ estatisticas.totalChamados }}
            </td>
          </tr>
          <tr>
            <td>
              Total de chamados em aberto: {{ estatisticas.totalEmAberto }}
            </td>
          </tr>
          <tr>
            <td>
              Total de chamados em atendimento:
              {{ estatisticas.totalEmAtendimento }}
            </td>
          </tr>
          <tr>
            <td>
              Total de chamados concluídos: {{ estatisticas.totalConcluidos }}
            </td>
          </tr>
        </tbody>
        <thead v-if="id_permissao === 3">
          <tr>
            <th>{{ msgRelatorios }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "AtualizacoesHome",
  data() {
    return {
      Chamados: [],
      atualizacoesMsg: "",
      estatisticas: {},
      msgRelatorios: "",
      id_permissao: Number(sessionStorage.getItem("permission")),
    };
  },
  created() {
    import("../assets/css/component/MeusChamados.css");
    this.onListarAtualizacoes();
  },
  methods: {
    onListarAtualizacoes() {
      const id_user = sessionStorage.getItem("id_user");
      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/chamados/read/atualizacoes.php?action=getAtualizacoes&id_user=${id_user}`
        )
        .then((res_atualizacao) => {
          if (res_atualizacao.data.error === true) {
            console.log("Server response:", res_atualizacao.data.msg);
          } else {
            this.Chamados = res_atualizacao.data.chamados;
            this.atualizacoesMsg =
              res_atualizacao.data.msg || "Nenhuma atualização";

            // Dados das estatísticas
            this.estatisticas = res_atualizacao.data.estatisticas || {};

            // Mensagem para relatórios
            this.msgRelatorios = res_atualizacao.data.msg_relatorios || "";
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>
