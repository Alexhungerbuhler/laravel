import { createApp } from 'vue'
import App from './pages/app.vue'
import router from './routes/index.js' // Assurez-vous que le chemin est correct


// si tu as besoin d'un token stocké en localStorage :
const token = localStorage.getItem('api_token')
if (token) {
  window.fetch = ((orig) =>
    (url, opts = {}) => {
      opts.headers = {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...opts.headers,
      }
      return orig(url, opts)
    }
  )(window.fetch)
}

createApp(App)
  .use(router)
  .mount('#app')

