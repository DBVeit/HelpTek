<template>
    <div>
      <h2>Resetar Senha</h2>
      <form @submit.prevent="requestReset">
        <label for="email">Digite seu e-mail:</label>
        <input type="email" id="email" v-model="email" required />
        <button type="submit">Enviar</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: ''
      };
    },
    methods: {
      requestReset() {
        fetch('/api/request-reset', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email: this.email })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('E-mail de reset enviado.');
          } else {
            alert('Erro ao enviar e-mail de reset.');
          }
        })
        .catch(error => {
          console.error('Erro:', error);
        });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Adicionar estilos */
  </style>
  