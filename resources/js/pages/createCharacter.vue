<template>
    <div class="max-w-md mx-auto mt-10">
      <h1 class="text-2xl font-bold mb-4">Créer ton personnage</h1>
      <form @submit.prevent="submit">
        <label class="block mb-2">Nom</label>
        <input v-model="name" required
               class="w-full p-2 border rounded mb-4"
               placeholder="Entrez un nom"/>
        <button type="submit"
                class="w-full bg-blue-600 text-white p-2 rounded">
          Créer
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const name = ref('')
  const router = useRouter()
  
  async function submit() {
    try {
      // Appel POST sur l'endpoint API
      await axios.post('/api/character', { name: name.value })
      router.push({ name: 'Dashboard' })
    } catch (err) {
      alert(err.response?.data?.message || 'Erreur lors de la création')
    }
  }
  </script>
  