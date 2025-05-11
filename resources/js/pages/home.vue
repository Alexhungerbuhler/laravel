<template>
    <NavBar />
  
    <div class="text-center py-4">
      <h1 class="text-4xl font-bold mb-4">Bienvenue dans WooW !</h1>
      <p class="mb-8 text-lg text-gray-600 max-w-xl mx-auto">
        Incarnez un héros sur une carte 5×5, combattez, lootez, survivez !
      </p>
  
      <div class="space-x-4">
        <a href="/login" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Login</a>
        <a href="/register" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Register</a>
      </div>
    </div>
  </template>
  
  <script setup>
  import NavBar from '@/components/NavBar.vue'
  
  </script>
  
<style scoped>
/* Container centré verticalement et horizontalement */
.py-16 {
  padding-top: 4rem;
  padding-bottom: 4rem;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  text-align: center; /* Centre le texte */
}

/* Titre principal */
h1 {
  font-size: 2.5rem; /* 4xl */
  font-weight: 700;
  color: #1e3a8a; /* indigo-800 */
  text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
  margin-bottom: 1rem;
  text-align: center; /* Centre le texte */
}

/* Paragraphe descriptif */
p {
  font-size: 1.125rem; /* lg */
  color: #4b5563; /* gray-600 */
  line-height: 1.6;
  text-align: center; /* Centre le texte */
}

/* Boutons */
a,
.router-link-active {
  display: inline-block;
  font-weight: 600;
  font-size: 1rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  text-align: center; /* Centre le texte */
}

a.bg-blue-500 {
  background: #3b82f6;
  color: #ffffff;
}
a.bg-blue-500:hover {
  background: #2563eb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

a.bg-green-500 {
  background: #10b981;
  color: #ffffff;
}
a.bg-green-500:hover {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

/* Limite la largeur du paragraphe et centre */
.max-w-xl {
  max-width: 36rem; /* ~576px */
  margin-left: auto;
  margin-right: auto;
  text-align: center; /* Centre le texte */
}

/* Texte gris pour le paragraphe */
.text-gray-600 {
  color: #4b5563;
}
</style>
