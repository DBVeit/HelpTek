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
          <label>Anexo</label>
          <input
            type="file"
            id="attachment"
            ref="attachment"
            name="anexo"
            @change="handleFileUpload"
            multiple
          />
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
import { storage } from "../firebase"; // Importe o Firebase Storage

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
      showErrors: false,
      anexos: [], // Array para armazenar os arquivos anexados
      anexosUrls: [], // Array para armazenar as URLs dos arquivos no Firebase
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
    async onCriarChamado() {
      let data = new FormData();

      if (
        !this.ChamadoData.titulo ||
        !this.ChamadoData.descricao ||
        !this.ChamadoData.gravidade ||
        !this.ChamadoData.urgencia ||
        !this.ChamadoData.tendencia
      ) {
        this.showErrors = true;
        return;
      }

      this.showErrors = false;

      // Primeiro faz o upload dos arquivos para o Firebase
      try {
        const uploadPromises = this.anexos.map((anexo) => {
          const storageRef = storage.ref(`anexos/${anexo.name}`);
          return storageRef.put(anexo).then((snapshot) => {
            return snapshot.ref.getDownloadURL(); // Retorna a URL pública do arquivo
          });
        });

        // Aguarda o upload de todos os arquivos e captura as URLs
        this.anexosUrls = await Promise.all(uploadPromises);
      } catch (error) {
        console.error("Erro ao fazer upload dos anexos: ", error);
        return;
      }

      // Agora envia os dados e as URLs dos anexos para o backend
      data.append("titulo", this.ChamadoData.titulo);
      data.append("descricao", this.ChamadoData.descricao);
      data.append("gravidade", this.ChamadoData.gravidade);
      data.append("urgencia", this.ChamadoData.urgencia);
      data.append("tendencia", this.ChamadoData.tendencia);
      data.append("id_user", this.ChamadoData.id_user);
      // Adiciona as URLs dos anexos
      this.anexosUrls.forEach((url, index) => {
        data.append(`anexoUrl${index}`, url);
      });

      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/insertChamado.php?action=InsertChamado",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error === true) {
            this.showAlert(res.data.msg);
          } else {
            this.showAlert(res.data.msg);
            this.clearFormFields();
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
      this.anexos = [];
      this.anexosUrls = [];
    },
    handleFileUpload(event) {
      const files = event.target.files;
      for (let i = 0; i < files.length; i++) {
        this.anexos.push(files[i]);
      }
    },
    removeAnexo(index) {
      this.anexos.splice(index, 1);
    },
  },
};
</script>
