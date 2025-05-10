// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import home            from '../Pages/home.vue'
import CreateCharacter from '../Pages/createCharacter.vue'
import Dashboard       from '../Pages/Dashboard.vue'

const routes = [
  // page d’accueil
  { path: '/',               name: 'home',             component: home },
  // création de perso
  { path: '/character/create', name: 'character.create', component: CreateCharacter },
  // dashboard – accessible une fois connecté et perso créé
  { path: '/dashboard',name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },

]

export default createRouter({
  history: createWebHistory(),
  routes,
})
