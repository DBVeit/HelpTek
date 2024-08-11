<template>
  <div class="ticket-form-container">
    <div class="message-box" v-if="showMessage">
      <div class="message-content">
        <span>{{ message }}</span>
      </div>
    </div>
    <div class="page_title">
      <h1>Criar novo chamado</h1>
    </div>
    <div>
      <form method="POST" @submit.prevent="onCriarChamado()">
        <div class="form-group">
          <label>Título *</label>
          <input
            type="text"
            id="subject"
            name="titulo"
            v-model="ChamadoData.titulo"
            required
          />
        </div>
        <div class="form-group">
          <label>Descrição *</label>
          <textarea
            id="description"
            name="descricao"
            rows="4"
            v-model="ChamadoData.descricao"
            required
          ></textarea>
        </div>
        <div class="form-group">
          <label
            >Prioridade *
            <a class="form-tip"
              >(Será calculada sistemicamente com base em
              GravidadeXUrgênciaXTendência)</a
            ></label
          >
        </div>
        <div class="form-group">
          <select
            name="gravidade"
            id="gravidade"
            v-model="ChamadoData.gravidade"
            required
          >
            <option default value="" disabled>Gravidade</option>
            <option
              v-for="gravidade in prioridadesGravidade"
              :key="gravidade.id_prioridade"
              :value="gravidade.valor_prioridade"
            >
              {{ gravidade.descricao_categoria }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <select
            name="urgencia"
            id="urgencia"
            v-model="ChamadoData.urgencia"
            required
          >
            <option default value="" disabled>Urgência</option>
            <option
              v-for="urgencia in prioridadesUrgencia"
              :key="urgencia.id_prioridade"
              :value="urgencia.valor_prioridade"
            >
              {{ urgencia.descricao_categoria }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <select
            name="tendencia"
            id="tendencia"
            v-model="ChamadoData.tendencia"
            required
          >
            <option default value="" disabled>Tendência</option>
            <option
              v-for="tendencia in prioridadesTendencia"
              :key="tendencia.id_prioridade"
              :value="tendencia.valor_prioridade"
            >
              {{ tendencia.descricao_categoria }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label>Anexo</label>
          <input type="file" id="attachment" name="anexo" />
        </div>
        <div class="form-group">
          <p class="form-tip">* Campos de preenchimento obrigatório</p>
          <button type="submit" class="submit-button-chamado">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "CriarChamado",
  data() {
    const id_user = sessionStorage.getItem("id_user");
    return {
      ChamadoData: {
        titulo: "",
        descricao: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_user: id_user,
      },
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      showMessage: false,
      message: "",
    };
  },
  created() {
    this.fetchPrioridades();
  },
  methods: {
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
    onCriarChamado() {
      let data = new FormData();
      data.append("titulo", this.ChamadoData.titulo);
      data.append("descricao", this.ChamadoData.descricao);
      data.append("gravidade", this.ChamadoData.gravidade);
      data.append("urgencia", this.ChamadoData.urgencia);
      data.append("tendencia", this.ChamadoData.tendencia);
      data.append("id_user", this.ChamadoData.id_user);
      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/insertChamado.php?action=InsertChamado",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error == true) {
            this.showAlert(res.data.msg);
            this.clearFormFields();
          } else {
            this.showAlert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showAlert(message) {
      this.message = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 8000);
    },
    clearFormFields() {
      this.ChamadoData.titulo = "";
      this.ChamadoData.descricao = "";
      this.ChamadoData.gravidade = "";
      this.ChamadoData.urgencia = "";
      this.ChamadoData.tendencia = "";
      this.$refs.attachment.value = "";
    },
    closeMessage() {
      this.showMessage = false;
    },
  },
};
</script>
