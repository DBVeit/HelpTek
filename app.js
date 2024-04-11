new Vue({
    el: '#app',
    data() {
      return {
        username: '',
        password: '',
        error: ''
      };
    },
    methods: {
      async login() {
        try {
          const response = await axios.post('/login', {
            username: this.username,
            password: this.password
          });
          // Handle successful login (e.g., redirect to dashboard)
        } catch (error) {
          this.error = 'Usuário ou senha incorretos.';
        }
      }
    }
  });
  