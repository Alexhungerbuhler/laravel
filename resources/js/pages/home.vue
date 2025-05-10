<!-- resources/js/Pages/Home.vue -->
<template>
    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
      <!-- Header -->
      <header class="flex justify-end p-4">
    <nav class="space-x-4">
      <a href="/login"    class="text-blue-600 hover:underline">Login</a>
      <a href="/register" class="text-blue-600 hover:underline">Register</a>
    </nav>
  </header>
  
      <!-- Contenu principal -->
      <main class="flex-grow flex flex-col items-center justify-center text-center px-4">
        <h1 class="title">
          Bienvenue dans Woow Game !
        </h1>
        <p class="description">
          Incarnez un vaillant guerrier humain, explorez une carte mystérieuse de 5×5 cases,
          affrontez des monstres, collectez des objets et atteignez la sortie !  
          Prêt à relever le défi ?
        </p>
        <a href="/login" class="main-button">
          Commencer l'aventure
        </a>
      </main>
  
      <!-- Footer facultatif -->
      <footer class="w-full max-w-md px-6 py-4 text-center text-sm text-gray-500">
        &copy; 2025 Woow Game
      </footer>
    </div>
  </template>
  
  <script setup>
  // On peut déterminer le statut d'authentification
  // via une prop, un store ou simplement laisser Laravel
  // injecter window.Laravel.authenticated si tu le souhaites.
  import { ref, onMounted } from 'vue';
  
  const isAuthenticated = ref(false);
  
  onMounted(() => {
    // Exemple basique : si Laravel met un flag JS
    // window.Laravel.userId, ou via un cookie JWT
    if (window.Laravel?.userId) {
      isAuthenticated.value = true;
    }
  });
  </script>
  
  <style scoped>
  
  /* Ajustements CSS pour le style */
  .nav-link {
    color: #1d4ed8;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: background-color 0.3s;
  }
  
  .nav-link:hover {
    background-color: #e0f2fe;
  }
  
  .title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1.5rem;
  }
  
  .description {
    font-size: 1.125rem;
    color: #4b5563;
    max-width: 600px;
    margin-bottom: 2rem;
  }
  
  .main-button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: #2563eb;
    color: white;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }
  
  .main-button:hover {
    background-color: #1e40af;
  }
  </style>