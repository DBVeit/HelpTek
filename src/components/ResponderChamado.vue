<template>
  <div class="criar-chamado-container">
    <h1>Responder Chamado</h1>
    <form @submit.prevent="responderChamado">
      <div class="form-group">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" v-model="chamado.titulo" readonly />
      </div>
      <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea id="descricao" rows="4" v-model="chamado.descricao" readonly></textarea>
      </div>
      <div class="form-group">
        <label for="categoria">Categoria do Serviço</label>
        <select id="categoria" v-model="categoriaSelecionada" :disabled="!isEditing">
          <option v-for="categoria in categoriasServico" :key="categoria.id" :value="categoria.id">{{ categoria.nome }}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="ocorrencia">Categoria da Ocorrência</label>
        <select id="ocorrencia" v-model="ocorrenciaSelecionada" :disabled="!isEditing">
          <option v-for="ocorrencia in categoriasOcorrencia" :key="ocorrencia.id" :value="ocorrencia.id">{{ ocorrencia.nome }}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="descricaoSolucao">Descrição da Solução</label>
        <textarea id="descricaoSolucao" rows="4" v-model="descricaoSolucao"></textarea>
      </div>
      <button type="submit">Responder Chamado</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ResponderChamado",
  data() {
    return {
      chamado: {},
      categoriaSelecionada: null,
      ocorrenciaSelecionada: null,
      descricaoSolucao: ""
    };
  },
  created() {
    this.carregarChamado();
    this.carregarCategorias();
  },
  methods: {
    carregarChamado() {
      // Aqui você deve buscar o chamado selecionado e preencher os dados
      // Vou deixar vazio pois depende de como você está gerenciando os chamados
      this.chamado = {
        titulo: "Título do Chamado",
        descricao: "Descrição do Chamado"
        // Preencher os demais campos conforme necessário
      };
    },
    carregarCategorias() {
      // Aqui você deve buscar as categorias de serviço e de ocorrência
      // Vou deixar vazio pois depende de como você está gerenciando as categorias
      this.categoriasServico = [
        { id: 1, nome: "Categoria 1" },
        { id: 2, nome: "Categoria 2" }
        // Preencher com as categorias de serviço
      ];
      this.categoriasOcorrencia = [
        { id: 1, nome: "Ocorrência 1" },
        { id: 2, nome: "Ocorrência 2" }
        // Preencher com as categorias de ocorrência
      ];
    },
    responderChamado() {
      // Aqui você deve enviar a resposta do chamado para o servidor
      // Utilize o axios ou outra biblioteca para fazer a requisição HTTP
      console.log("Responder Chamado");
    }
  }
};
</script>

<style scoped>
/* Estilos CSS */
</style>
