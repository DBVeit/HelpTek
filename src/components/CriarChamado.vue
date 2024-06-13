<template>
  <div class="ticket-form-container">
    <h1>Criar novo chamado</h1>
    <form method="POST" @submit.prevent="onCriarChamado()">
      <div class="form-group">
        <label>Título</label>
        <input
          type="text"
          id="subject"
          name="titulo"
          v-model="ChamadoData.titulo"
          required
        />
      </div>
      <div class="form-group">
        <label>Descrição</label>
        <textarea
          id="description"
          name="descricao"
          rows="4"
          v-model="ChamadoData.descricao"
          required
        ></textarea>
      </div>
      <div class="form-group">
        <label>Prioridade</label>
        <select name="gravidade" v-model="ChamadoData.gravidade" required>
          <option default value="">Gravidade</option>
          <option value="1">1 - O dano é pouco importante</option>
          <option value="2">2 - O dano é relativamente importante</option>
          <option value="3">3 - O dano é importante</option>
          <option value="4">4 - O dano é muio importante</option>
          <option value="5">5 - O dano é extremamente importante</option>
        </select>
        <select name="urgencia" v-model="ChamadoData.urgencia" required>
          <option default value="">Urgência</option>
          <option value="1">1 - Não há pressa</option>
          <option value="2">2 - Pode-se aguardar</option>
          <option value="3">3 - Tomar uma ação relativamente urgente</option>
          <option value="4">4 - Tomar uma ação urgente</option>
          <option value="5">5 - Tomar uma ação bastante urgente</option>
        </select>
        <select name="tendencia" v-model="ChamadoData.tendencia" required>
          <option default value="">Tendência</option>
          <option value="1">1 - Tende à desaparecer completamente</option>
          <option value="2">2 - Tende à melhorar</option>
          <option value="3">3 - Tende à permanecer</option>
          <option value="4">4 - Tende à piorar</option>
          <option value="5">5 - Tende à piorar muito</option>
        </select>
      </div>
      <div class="form-group">
        <label>Anexo</label>
        <input type="file" id="attachment" name="anexo" />
      </div>
      <br />
      <button type="submit" class="submit-button">Enviar</button>
    </form>
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
    };
  },
  methods: {
    onCriarChamado() {
      let data = new FormData();
      data.append("titulo", this.ChamadoData.titulo);
      data.append("descricao", this.ChamadoData.descricao);
      data.append("gravidade", this.ChamadoData.gravidade);
      data.append("urgencia", this.ChamadoData.urgencia);
      data.append("tendencia", this.ChamadoData.tendencia);
      data.append("id_user", this.ChamadoData.id_user);
      axios
        .post(
          "http://localhost/projeto/helptek/php/api/functions/insertChamado.php?action=InsertChamado",
          data
        )
        .then((res) => {
          console.log("Server response:", res.data);
          if (res.data.error == true) {
            alert(res.data.msg);
          } else {
            alert(res.data.msg);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>
