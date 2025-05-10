<template>
  <div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Connexion</h2>
    <form @submit.prevent="login">
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" v-model="email" id="email" class="mt-1 block w-full" required>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
        <input type="password" v-model="password" id="password" class="mt-1 block w-full" required>
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Se connecter</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')

function login() {
  // Logique pour la connexion
  console.log('Email:', email.value, 'Password:', password.value)
}
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici */
</style>
