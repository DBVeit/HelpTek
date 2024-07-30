<template>
  <div class="modal" :class="{ 'is-active': showModal }">
    <div class="modal-background" @click="closeModal"></div>
    <div class="modal-content">
      <div class="box">
        <button class="delete" aria-label="close" @click="closeModal"></button>
        <p>{{ modalMessage }}</p>
        <!-- Adicione os campos de login aqui -->
        <input type="text" class="input-field" placeholder="Username" />
        <input type="password" class="input-field" placeholder="Password" />
        <button class="login-button">Login</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      modalMessage: "",
    };
  },
  methods: {
    openModal(message) {
      this.modalMessage = message;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
  },
};
</script>

<style scoped>
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal.is-active {
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-background {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: #fff;
  border-radius: 4px;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  max-width: 90%;
  width: 400px; /* Ajuste conforme necessário */
  margin: 10px;
}

.delete {
  position: absolute;
  right: 15px;
  top: 15px;
  cursor: pointer;
  border: none;
  background-color: transparent;
  color: #333;
}

.input-field {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border-radius: 4px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.login-button {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border-radius: 4px;
  border: none;
  background-color: #ff5757; /* Cor original do botão */
  color: #f4f4f4;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #333; /* Cor original do hover */
}

@media (max-width: 768px) {
  .modal-content {
    padding: 15px;
    width: 90%; /* Altera para largura responsiva */
  }

  .box {
    padding: 10px;
  }

  .delete {
    right: 10px;
    top: 10px;
  }

  .input-field, .login-button {
    width: 25%; /* Garanta que os campos e botão ocupem toda a largura */
    padding: 12px;
  }
}

@media (max-width: 480px) {
  .modal-content {
    padding: 10px;
    width: 95%; /* Altera para largura responsiva */
  }

  .box {
    padding: 5px;
  }

  .delete {
    right: 5px;
    top: 5px;
  }

  .input-field, .login-button {
    width: 25%; /* Garanta que os campos e botão ocupem toda a largura */
    padding: 14px;
  }
}
</style>
