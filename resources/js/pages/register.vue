<template>
  <div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Inscription</h2>
    <form @submit.prevent="register">
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" v-model="name" id="name" class="mt-1 block w-full" required>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" v-model="email" id="email" class="mt-1 block w-full" required>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
        <input type="password" v-model="password" id="password" class="mt-1 block w-full" required>
      </div>
      <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">S'inscrire</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')

function register() {
  // Logique pour l'inscription
  console.log('Name:', name.value, 'Email:', email.value, 'Password:', password.value)
}
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici */
</style>
