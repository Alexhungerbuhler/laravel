import { createRouter, createWebHistory } from 'vue-router'

// Vos pages Vue
import Home            from '@/pages/home.vue'
import Dashboard       from '@/pages/dashboard.vue'
import CreateCharacter from '@/pages/createCharacter.vue'


const routes = [
  { path: '/',               name: 'home',             component: Home },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/character/create',
    name: 'create-character',
    component: CreateCharacter,
    meta: { requiresAuth: true }
  },
  { path: '/:pathMatch(.*)*', redirect: '/' }

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Petite garde pour protéger les routes “requiresAut

export default router
