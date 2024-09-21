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
          />
          <span class="form-tip" v-if="!ChamadoData.titulo && showErrors"
            >*Preechimento obrigatório!</span
          >
        </div>
        <div class="form-group">
          <label>Descrição *</label>
          <textarea
            id="description"
            name="descricao"
            rows="4"
            v-model="ChamadoData.descricao"
          ></textarea>
          <span class="form-tip" v-if="!ChamadoData.descricao && showErrors"
            >*Preechimento obrigatório!</span
          >
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
          <span class="form-tip" v-if="!ChamadoData.gravidade && showErrors"
            >*Preechimento obrigatório!</span
          >
        </div>
        <div class="form-group">
          <select name="urgencia" id="urgencia" v-model="ChamadoData.urgencia">
            <option default value="" disabled>Urgência</option>
            <option
              v-for="urgencia in prioridadesUrgencia"
              :key="urgencia.id_prioridade"
              :value="urgencia.valor_prioridade"
            >
              {{ urgencia.descricao_categoria }}
            </option>
          </select>
          <span class="form-tip" v-if="!ChamadoData.urgencia && showErrors"
            >*Preechimento obrigatório!</span
          >
        </div>
        <div class="form-group">
          <select
            name="tendencia"
            id="tendencia"
            v-model="ChamadoData.tendencia"
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
          <span class="form-tip" v-if="!ChamadoData.tendencia && showErrors"
            >*Preechimento obrigatório!</span
          >
        </div>
        <div class="form-group">
          <!--<label>Dias com Problema *</label>
          <input
            type="number"
            name="diasCProb"
            v-model="ChamadoData.diasCProb"
          />-->
          <div class="form-group">
            <label>Anexo</label>
            <input
              type="file"
              id="attachment"
              ref="attachment"
              name="anexo"
              @change="handleFileUpload"
              multiple
            />
          </div>
          <table class="anexo-grid" v-if="anexos.length > 0">
            <thead>
              <tr>
                <th>Arquivo</th>
                <th>Tamanho</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(anexo, index) in anexos" :key="index">
                <td>{{ anexo.name }}</td>
                <td>{{ anexo.size }} bytes</td>
                <td>
                  <button class="bt-remove-anexo" @click="removeAnexo(index)">
                    <i class="bi bi-x"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="criar-chamado-form-confirm">
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
    const session_token = localStorage.getItem("token");
    return {
      ChamadoData: {
        titulo: "",
        descricao: "",
        //diasCProb: "",
        gravidade: "",
        urgencia: "",
        tendencia: "",
        id_user: id_user,
        session_token: session_token,
      },
      prioridadesGravidade: [],
      prioridadesUrgencia: [],
      prioridadesTendencia: [],
      showMessage: false,
      message: "",
      showErrors: false,
      anexos: [], // Array para armazenar os arquivos anexados
    };
  },
  created() {
    this.fetchPrioridades();
    /*console.log(sessionStorage.getItem("id_user"));
    console.log(localStorage.getItem("token"));*/
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

      // Verifique se há algum campo obrigatório vazio
      if (
        !this.ChamadoData.titulo ||
        !this.ChamadoData.descricao ||
        !this.ChamadoData.gravidade ||
        !this.ChamadoData.urgencia ||
        !this.ChamadoData.tendencia
      ) {
        // Não prosseguir se houver erros
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      let id_user = sessionStorage.getItem("id_user");
      let session_token = localStorage.getItem("token");
      data.append("titulo", this.ChamadoData.titulo);
      data.append("descricao", this.ChamadoData.descricao);
      data.append("gravidade", this.ChamadoData.gravidade);
      data.append("urgencia", this.ChamadoData.urgencia);
      //data.append("diasCProb", this.ChamadoData.diasCProb);
      data.append("tendencia", this.ChamadoData.tendencia);
      data.append("id_user", this.ChamadoData.id_user);
      // Adiciona os arquivos ao FormData
      this.anexos.forEach((anexo, index) => {
        data.append(`anexo${index}`, anexo);
      });

      // Cria um objeto para armazenar os dados
      let dataEntries = {};
      data.forEach((value, key) => {
        dataEntries[key] = value;
      });
      console.log(dataEntries); // Exibe o objeto com os dados
      //console.log(session_token);

      axios
        .get(
          `http://localhost/projeto/helptek/php/api/functions/session/checkUser.php?id_user=${id_user}&session_token=${session_token}`
        )
        .then((res) => {
          if (res.data.error === false) {
            console.log("Server response:", res.data.msg);
            const idfr_code_user = res.data.user;
            data.append("idfr_code_user", idfr_code_user);
            axios
              .post(
                "http://localhost/projeto/helptek/php/api/functions/chamados/create/insertChamado.php",
                data
              )
              .then((res_insert) => {
                console.log("Server response:", res_insert.data);
                if (res_insert.data.error === true) {
                  this.showAlert(res_insert.data.msg);
                } else {
                  this.showAlert(res_insert.data.msg);
                  this.clearFormFields();
                }
              })
              .catch((err) => {
                console.log(err);
              });
            //this.showAlert(res.data.msg);
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
      this.anexos = []; // Limpa a lista de anexos
    },
    handleFileUpload(event) {
      const files = event.target.files;
      for (let i = 0; i < files.length; i++) {
        this.anexos.push(files[i]);
      }
    },
    removeAnexo(index) {
      this.anexos.splice(index, 1); // Remove o anexo da lista
    },
  },
};
</script>
