<template>
  <div class="modal" v-if="showModal">
    <div class="modal-content">
      <span class="close" @click="closeModal">&times;</span>
      <h2>Editar Chamado</h2>
      <!-- Formulário para editar os detalhes do chamado -->
      <!-- Use v-model para vincular os campos aos dados do chamado -->
      <form @submit.prevent="saveChanges">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" v-model="editedChamado.titulo" />

        <label for="prioridade">Prioridade:</label>
        <input type="text" id="prioridade" v-model="editedChamado.prioridade" />

        <!-- Adicione mais campos conforme necessário -->

        <button type="submit">Salvar</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      editedChamado: {
        id_chamado: "",
        titulo: "",
        prioridade: "",
        // Adicione mais propriedades conforme necessário
      },
    };
  },
  methods: {
    openModal(chamado) {
      // Copie os dados do chamado para o objeto editedChamado
      this.editedChamado = { ...chamado };
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    saveChanges() {
      // Implemente o método para salvar as alterações no chamado
      // Pode enviar uma requisição HTTP para atualizar os dados no servidor
      // Depois de salvar, feche o modal
      this.closeModal();
    },
  },
};
</script>

<style scoped>
/* Estilos para o modal */
.modal {
  /* Estilos para o modal, como posicionamento, fundo, etc. */
}

/* Estilos para o conteúdo do modal */
.modal-content {
  /* Estilos para o conteúdo do modal, como cor de fundo, margem, etc. */
}

/* Estilos para o botão de fechar o modal */
.close {
  /* Estilos para o botão de fechar, como cor, posição, etc. */
}
</style>
