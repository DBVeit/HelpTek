<template>
    <div>
      <h2>Nova Senha</h2>
      <form @submit.prevent="resetPassword">
        <input type="hidden" v-model="token" />
        <label for="password">Nova Senha:</label>
        <input type="password" id="password" v-model="password" required />
        <button type="submit">Resetar Senha</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        token: new URLSearchParams(window.location.search).get('token'),
        password: ''
      };
    },
    methods: {
      resetPassword() {
        fetch('/api/reset-password', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            token: this.token,
            password: this.password
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Senha resetada com sucesso.');
          } else {
            alert('Erro ao resetar senha.');
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
  /* Adicione estilos conforme necessário */
  </style>
  