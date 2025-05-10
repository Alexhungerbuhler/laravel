<template>
  <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <!-- Flash messages -->
    <div
      v-if="flash.success"
      class="p-4 bg-green-100 text-green-800 rounded"
    >{{ flash.success }}</div>
    <div
      v-if="flash.error"
      class="p-4 bg-red-100 text-red-800 rounded"
    >{{ flash.error }}</div>

    <!-- Si pas encore de personnage -->
    <div
      v-if="!character"
      class="bg-white p-6 rounded shadow text-center"
    >
      <p class="mb-4">Vous n’avez pas encore de personnage.</p>
      <a
        :href="routes.characterCreate"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >Créer mon personnage</a>
    </div>

    <!-- Sinon : dashboard classique -->
    <div
      v-else
      class="bg-white p-6 rounded shadow flex flex-col lg:flex-row gap-6"
    >
      <!-- GAUCHE : map + actions -->
      <div class="flex-1 space-y-4">
        <h3 class="text-lg font-bold mb-2">Map 5×5</h3>
        <div id="map-grid" class="grid grid-cols-5 gap-1">
          <div
            v-for="(cell, idx) in cells"
            :key="idx"
            :class="[
              'w-12 h-12 flex items-center justify-center border text-sm',
              idx === currentIndex ? 'bg-yellow-200' : ''
            ]"
          >
            <template v-if="idx === currentIndex">
              🙂
            </template>
            <template v-else>
              <span v-if="cell.type === 'exit'">🚩</span>
              <span v-else-if="cell.type === 'monster'">🗡</span>
              <span v-else-if="cell.type === 'item'">🎁</span>
              <span v-else>–</span>
            </template>
          </div>
        </div>

        <!-- Boutons d'action 2×2 -->
        <div class="grid grid-cols-2 gap-2 mt-4">
          <button class="w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            Attaquer
          </button>
          <button class="w-full px-3 py-2 bg-blue-400 text-white rounded hover:bg-blue-500">
            Fuir
          </button>
          <button class="w-full px-3 py-2 bg-green-400 text-white rounded hover:bg-green-500">
            Potion
          </button>
          <button class="w-full px-3 py-2 bg-yellow-400 rounded hover:bg-yellow-500">
            Butin
          </button>
        </div>
      </div>

      <!-- DROITE : stats & déplacement -->
      <div class="w-full lg:w-1/3 space-y-4">
        <h3 class="text-lg font-bold mb-2">Votre personnage</h3>
        <ul class="space-y-1">
          <li><strong>Nom :</strong> {{ character.name }}</li>
          <li><strong>Race :</strong> {{ character.race }}</li>
          <li><strong>Classe :</strong> {{ character.class }}</li>
          <li><strong>HP :</strong> {{ character.hp }} / {{ character.max_hp }}</li>
          <li><strong>Puissance :</strong> {{ character.power }}</li>
          <li><strong>Armure :</strong> {{ character.armor }}</li>
          <li><strong>Niveau :</strong> {{ character.level }}</li>
          <li><strong>XP :</strong> {{ character.xp }}</li>
        </ul>

        <form
          :action="routes.characterDestroy"
          method="POST"
          @submit.prevent="destroyCharacter"
        >
          <input type="hidden" name="_token" :value="csrfToken" />
          <input type="hidden" name="_method" value="DELETE" />
          <button
            type="submit"
            class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          >
            Supprimer mon personnage
          </button>
        </form>

        <!-- déplacements -->
        <div class="grid grid-cols-3 gap-2 mt-4">
          <div></div>
          <button @click="move(0,-1)" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">↑</button>
          <div></div>

          <button @click="move(-1,0)" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">←</button>
          <div></div>
          <button @click="move(1,0)" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">→</button>

          <div></div>
          <button @click="move(0,1)" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">↓</button>
          <div></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  name: 'Dashboard',
  setup() {
    // 1) Récupération du payload Blade → JS
    const { character, map, flash } = window.APP_PAYLOAD;
    const characterRef = ref(character);
    const mapRef       = ref(Array.isArray(map) ? map : []);
    const flashRef     = ref(flash || { success: null, error: null });

    // 2) Position courante
    const posX = ref(characterRef.value?.x || 0);
    const posY = ref(characterRef.value?.y || 0);

    // 3) Flatten de la map pour l'affichage
    const cells = computed(() => {
      if (!Array.isArray(mapRef.value)) return [];
      return mapRef.value.flatMap(row => Array.isArray(row) ? row : []);
    });
    const currentIndex = computed(() => posY.value * 5 + posX.value);

    // 4) Routes Laravel nommées
    const routes = {
      characterCreate:  '/character/create',
      characterDestroy: '/character',  // si resource('character')
    };
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content');

    // 5) Méthodes
    const move = (dx, dy) => {
      const nx = posX.value + dx;
      const ny = posY.value + dy;
      if (nx < 0 || nx > 4 || ny < 0 || ny > 4) return;
      posX.value = nx;
      posY.value = ny;
      // si sortie
      const idx = ny * 5 + nx;
      if (cells.value[idx]?.type === 'exit') {
        alert('🎉 Félicitations, vous avez atteint la sortie !');
      }
    };

    const destroyCharacter = () => {
      if (!confirm('Supprimer ce personnage ?')) return;
      fetch(routes.characterDestroy, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Content-Type': 'application/json',
        }
      })
      .then(r => {
        if (r.ok) window.location.reload();
        else throw new Error('Erreur suppression');
      })
      .catch(console.error);
    };

    return {
      character:  characterRef,
      map:        mapRef,
      flash:      flashRef.value,
      cells, currentIndex, move,
      routes, csrfToken, destroyCharacter
    };
  }
};
</script>

<style scoped>

</style>
