<template>
    <div class="p-6">
      <h1 class="text-xl font-bold mb-4">Dashboard</h1>
      <div v-if="character">
        <p><strong>Nom :</strong> {{ character.name }}</p>
        <p><strong>HP :</strong> {{ character.hp }} / {{ character.max_hp }}</p>
        <p><strong>Power :</strong> {{ character.power }}</p>
        <p><strong>Armor :</strong> {{ character.armor }}</p>
      </div>
      <div v-else>
        <!-- Si pas de character, redirection gérée par beforeEnter -->
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const character = ref(null)
  const router = useRouter()
  
  onMounted(async () => {
    try {
      const res = await axios.get('/api/character')
      character.value = res.data
      if (!res.data) {
        router.push({ name: 'CreateCharacter' })
      }
    } catch {
      router.push({ name: 'Login' })
    }
  })
  </script>
  