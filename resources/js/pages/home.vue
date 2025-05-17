<template>
    <NavBar />
  
    <div class="max-w-3xl mx-auto py-12 px-4 space-y-6 text-gray-800">
      <h1 class="text-3xl font-bold text-center">Bienvenue dans <span class="text-blue-600">WooW</span> !</h1>
  
      <p class="text-lg text-gray-700 text-center">
        Incarnez un <strong>guerrier humain</strong> dans un monde rempli de dangers et de trésors.
        Explorez une carte 5×5, survivez aux monstres, équipez des objets puissants et atteignez la sortie vivante.
      </p>

      <div class="space-x-4 text-center">
        <a href="/dashboard" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Commencer l'aventure</a>
      </div>
  
      <div class="flex flex-wrap justify-between space-y-6">
        <section class="space-y-3 w-1/2">
          <h2 class="text-xl font-semibold">🎮 Déroulement du jeu</h2>
          <ul class="list-disc list-inside">
            <li>Créez un personnage (nom uniquement)</li>
            <li>Commencez avec <strong>20 HP</strong>, <strong>8 de puissance</strong> et <strong>0 armure</strong></li>
            <li>Explorez la carte 5x5 case par case</li>
          </ul>
        </section>
        <section class="space-y-3 w-1/2">
          <h2 class="text-xl font-semibold">🧭 Exploration</h2>
          <ul class="list-disc list-inside">
            <li>Déplacez-vous avec les <strong>flèches du clavier</strong> ou les boutons</li>
            <li>Chaque case peut contenir :
              <ul class="ml-6 list-disc">
                <li>🗡 Un monstre : combattez</li>
                <li>🎁 Un objet : équipez-le ou jetez-le</li>
                <li>🚩 Une sortie : atteignez-la pour gagner</li>
                <li>– Rien du tout</li>
              </ul>
            </li>
          </ul>
        </section>
        </div>


        <div class="flex flex-wrap justify-between space-y-6">
        <section class="space-y-3 w-1/2">
          <h2 class="text-xl font-semibold">⚔️ Les combats</h2>
          <ul class="list-disc list-inside">
            <li>Tour par tour : vous attaquez, puis le monstre répond</li>
            <li>Vos dégâts dépendent de votre <strong>puissance</strong></li>
            <li>L’<strong>armure</strong> réduit les dégâts reçus</li>
            <li>Le combat se termine à la mort de l’un des deux</li>
            <li>En cas de victoire : loot possible 🎁</li>
            <li>En cas de défaite : votre personnage est détruit</li>
          </ul>
        </section>
        <section class="space-y-3 w-1/2">
          <h2 class="text-xl font-semibold">🎁 Objets</h2>
          <ul class="list-disc list-inside">
            <li>Les objets peuvent être :
              <ul class="ml-6 list-disc">
                <li>🧪 Potions : soignent vos HP</li>
                <li>🛡 Armures : ajoutent de la défense</li>
                <li>⚔️ Épées : augmentent la puissance</li>
                <li>❤️ Bonus : augmentent vos HP maximum</li>
              </ul>
            </li>
            <li>Vous pouvez consulter vos objets équipés dans le tableau de bord</li>
          </ul>
        </section>
        </div>


        <div class="flex flex-wrap justify-between space-y-6">
        <section class="space-y-3 w-1/2">
          <h2 class="text-xl font-semibold">🏁 Victoire & ❌ Défaite</h2>
          <ul class="list-disc list-inside">
            <li>🚩 Atteignez la case sortie pour <strong>gagner</strong></li>
            <li>⚰️ Si vos HP tombent à 0 : le personnage est supprimé</li>
          </ul>
        </section>
  
      <section class="space-y-3">
        <h2 class="text-xl font-semibold">🚀 Aventure</h2>
        <p>Préparez-vous à une aventure épique dans le monde de WooW !</p>
      </section>
    </div>
  
      <section class="space-y-3">
        <h2 class="text-xl font-semibold">🚫 Avertissement</h2>
        <p>Attention, le jeu peut contenir des éléments de violence et des thèmes matures.</p>
      </section>
  
    </div>
  </template>

<script setup>
  import NavBar from '@/components/NavBar.vue'
  
  </script>
  
  <style scoped>
  /* CENTRAGE GLOBAL */
  .py-16 {
    padding-top: 4rem;
    padding-bottom: 4rem;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    text-align: center;
    background: linear-gradient(to bottom right, #f8fafc, #e0f2fe);
  }
  
  /* CONTAINER PRINCIPAL */
  .max-w-3xl {
    max-width: 768px;
    margin: 0 auto;
    padding: 1rem;
  }
  
  /* TITRES */
  h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e3a8a;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
    margin-bottom: 1rem;
  }
  
  h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e40af;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    text-align: left;

  }
  
  h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e40af;
  }
  
  /* PARAGRAPHES */
  p {
    font-size: 1.125rem;
    color: #374151;
    line-height: 1.6;
  }
  
  ul {
    text-align: left;
    color: #4b5563;
    line-height: 1.5;
  }
  
  ul li {
    margin-bottom: 0.25rem;
  }
  
  /* BOUTONS / LIENS */
  a,
  .router-link-active {
    display: inline-block;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    text-decoration: none;
    margin-top: 1.5rem;
  }
  
  a.bg-blue-600 {
    background: #2563eb;
    color: white;
  }
  a.bg-blue-600:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
  }
  
  a.bg-green-600 {
    background: #10b981;
    color: white;
  }
  a.bg-green-600:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
  }
  
  /* PETIT TEXTE */
  .text-xs {
    font-size: 0.75rem;
    color: #6b7280;
  }
  
  /* RESPONSIVE */
  @media (max-width: 640px) {
    h1 {
      font-size: 2rem;
    }
    h2 {
      font-size: 1.25rem;
    }
    p {
      font-size: 1rem;
    }
  }
  </style>
