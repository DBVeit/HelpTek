<template>
  <div id="newPassForm" v-if="newPassForm">
    <form method="POST" class="login-form" @submit.prevent="onTrocaSenha()">
      <div class="input-group">
        <input
          type="password"
          id="new_password"
          v-model="User.newPass"
          placeholder="Nova senha"
          name="password"
          autocomplete="off"
          required
        />
      </div>
      <div class="input-group">
        <input
          type="password"
          id="confirm_password"
          v-model="User.confPass"
          placeholder="Confirmar senha"
          autocomplete="off"
          required
        />
      </div>
      <button type="submit">Confirmar</button>
    </form>
  </div>
</template>
<script setup></script>
