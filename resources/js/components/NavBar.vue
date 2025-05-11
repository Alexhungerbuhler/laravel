<template>
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
      <div class="text-xl font-bold text-blue-600">
        <a href="/">WooW</a>
      </div>
  
      <div class="relative">
        <template v-if="!isLoggedIn">
          <a href="/login" class="nav-link">Login</a>
          <a href="/register" class="nav-link ml-4">Register</a>
        </template>
  
        <template v-else>
          <button @click="toggleDropdown" class="nav-link relative">
            {{ user.name }} ⌄
          </button>
          <div v-if="dropdownOpen" class="dropdown-menu">
            <a href="/profile" class="dropdown-item">Profil</a>
            <button @click="logout" class="dropdown-item text-red-500">Logout</button>
          </div>
        </template>
      </div>
    </nav>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const payload = window.APP_PAYLOAD || {}
  const user = payload.user || null
  const isLoggedIn = !!user
  
  const dropdownOpen = ref(false)
  
  function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
  }
  
  function logout() {
    fetch('/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })
      .then(() => window.location.href = '/')
      .catch(() => alert('Erreur lors de la déconnexion'))
  }
  </script>
  
  <style scoped>
  .nav-link {
    @apply text-gray-700 font-medium hover:text-blue-600;
  }
  
  .dropdown-menu {
    @apply absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-50;
  }
  
  .dropdown-item {
    @apply block w-full text-left px-4 py-2 hover:bg-gray-100;
  }
  </style>
  