<template>
  <NavBar />

  <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <h2 class="text-xl font-semibold mb-4 text-center">Créer votre personnage</h2>
    <form @submit.prevent="submit">
      <!-- Nom -->
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="name">Nom du personnage</label>
        <input
          id="name"
          v-model="name"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
          placeholder="Entrez un nom"
        />
      </div>

      <!-- Race (readonly) -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Race</label>
        <input
          v-model="race"
          type="text"
          readonly
          class="w-full border rounded bg-gray-100 px-3 py-2 cursor-not-allowed"
        />
      </div>

      <!-- Classe (readonly) -->
      <div class="mb-5">
        <label class="block mb-1 font-medium">Classe</label>
        <input
          v-model="clazz"
          type="text"
          readonly
          class="w-full border rounded bg-gray-100 px-3 py-2 cursor-not-allowed"
        />
      </div>

      <div class="text-center mt-4"> <!-- Ajout d'un espace supplémentaire entre le formulaire et le bouton -->
        <button
          type="submit"
          class="w-3 bg-indigo-600 px-3 py-2 text-black py-2 rounded-lg font-medium hover:bg-indigo-700 transition mx-auto"
        >
          Créer le personnage
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import NavBar from '@/components/NavBar.vue'

const router = useRouter()

// Champs du personnage
const name  = ref('')
const race  = ref('Humain')    // verrouillé, valeur par défaut
const clazz = ref('Guerrier')  // verrouillé, valeur par défaut

// Récupération du token CSRF depuis la <meta> (vérifiez qu’elle est bien présente dans app.blade.php)
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute('content')

async function submit() {
  try {
    const response = await fetch('/character', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        name:  name.value,
        race:  race.value,
        class: clazz.value,
      }),
    })

    if (response.ok) {
    // Recharge le dashboard pour obtenir le nouveau personnage
    window.location.href = '/dashboard'    
  } else {
      const error = await response.json()
      alert(error.message || 'Échec de la création.')
    }
  } catch (err) {
    console.error(err)
    alert('Erreur réseau, impossible de créer le personnage.')
  }
}
</script>
<style scoped>
/* Centrage vertical et horizontal pour que le formulaire ne colle pas en haut et soit centré */
div.max-w-sm {
  margin-top: 4rem;
  display: flex;
  justify-content: center;
}

button {
  transition: background-color 0.2s, transform 0.1s;
  border: 1px solid #000; 
}
button:hover {
  transform: translateY(-1px);
}
</style>
