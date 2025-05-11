<template>
  <NavBar />

  <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div v-if="!character" class="bg-white p-6 rounded shadow text-center">
      <p class="mb-4">Vous n'avez pas encore de personnage.</p>
      <router-link to="/character/create" class="btn-primary">Créer mon personnage</router-link>
    </div>

    <div v-else class="bg-white p-6 rounded shadow flex flex-col lg:flex-row gap-6">
      <!-- GAUCHE : Map et actions -->
      <div class="flex-1 space-y-4">
        <h3 class="text-center font-bold">Map 5×5</h3>
        <div class="grid grid-cols-5 gap-1">
          <div
            v-for="(cell, idx) in cellsList"
            :key="idx"
            :class="[ 'w-12 h-12 flex items-center justify-center border text-sm',
                      idx === currentIndex ? 'bg-yellow-200' : 'bg-white']"
            @click="tryMoveCell(idx)"
          >
            <template v-if="idx === currentIndex">🙂</template>
            <template v-else-if="cell.type==='exit'">🚩</template>
            <template v-else-if="cell.type==='monster'">🗡</template>
            <template v-else-if="cell.type==='item'">🎁</template>
            <template v-else>–</template>
          </div>
        </div>

        <!-- Mouvements -->
        <div class="grid grid-cols-3 gap-2 mt-4">
          <div></div><button @click="move(0, -1)" class="move-btn">↑</button><div></div>
          <button @click="move(-1, 0)" class="move-btn">←</button>
          <div></div>
          <button @click="move(1, 0)" class="move-btn">→</button>
          <div></div><button @click="move(0, 1)" class="move-btn">↓</button><div></div>
        </div>

        <!-- Combat -->
        <div v-if="inCombat" class="mt-4 p-4 bg-red-100 rounded">
          <h4 class="font-bold mb-2">Combat contre {{ monster.name }}</h4>
          <p><strong>Niveau :</strong> {{ monster.level }}</p>
          <p><strong>HP :</strong> {{ monster.hp }}</p>
          <p><strong>Attaque :</strong> {{ monster.power }}</p>
          <button @click="attack" class="btn-red">Attaquer</button>
          <button @click="flee" class="btn-gray ml-2">Fuir</button>
        </div>

        <!-- Loot -->
        <div v-if="showItemPanel" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white p-4 rounded shadow space-y-2">
            <h4 class="font-bold">Objet : {{ currentItem.name }}</h4>
            <p>Type : {{ currentItem.type }}</p>
            <div class="flex gap-2 justify-end">
              <button @click="equipItem(currentItem)" class="btn-red">Équiper</button>
              <button @click="discardItem()" class="btn-gray">Jeter</button>
              <button @click="closeItemPanel" class="btn-gray">Annuler</button>
            </div>
          </div>
        </div>
      </div>

      <!-- DROITE : Stats + Équipement -->
      <div class="w-full lg:w-1/3 space-y-4">
        <h3 class="text-center font-bold">Votre personnage</h3>
        <ul class="text-sm space-y-1">
          <li><strong>Nom :</strong> {{ character.name }}</li>
          <li><strong>Race :</strong> {{ character.race }}</li>
          <li><strong>Classe :</strong> {{ character.class }}</li>
          <li><strong>HP :</strong> {{ character.hp }} / {{ character.max_hp }}</li>
          <li><strong>Puissance :</strong> {{ character.power }}</li>
          <li><strong>Armure :</strong> {{ character.armor }}</li>
          <li><strong>Niveau :</strong> {{ character.level }}</li>
          <li><strong>XP :</strong> {{ character.xp }}</li>
        </ul>

        <h4 class="mt-6 font-bold">Équipement</h4>
        <ul class="text-sm list-disc list-inside">
          <li v-for="item in equippedItems" :key="item.id">{{ item.name }} ({{ item.type }})</li>
          <li v-if="equippedItems.length === 0">Aucun objet équipé</li>
        </ul>

        <button @click="destroyCharacter" class="btn-red-outline w-full">Supprimer le personnage</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import NavBar from '@/components/NavBar.vue'
import { ref, computed } from 'vue'

const { character: initChar, map = [] } = window.APP_PAYLOAD || {}
const character = ref({ ...initChar })
const mapRef = ref(Array.isArray(map) ? map : [])
const posX = ref(initChar?.x || 0)
const posY = ref(initChar?.y || 0)
const cellsList = computed(() => mapRef.value.flatMap(r => r))
const currentIndex = computed(() => posY.value * 5 + posX.value)

// Combat
const inCombat = ref(false)
const monster = ref({})

// Objet
const showItemPanel = ref(false)
const currentItem = ref(null)
const equippedItems = ref([]) // Liste d'objets équipés

function move(dx, dy) {
  const nx = posX.value + dx
  const ny = posY.value + dy
  if (nx < 0 || nx > 4 || ny < 0 || ny > 4) return
  tryMoveCell(ny * 5 + nx)
}

function tryMoveCell(idx) {
  if (inCombat.value) return
  const nx = idx % 5
  const ny = Math.floor(idx / 5)
  const cell = cellsList.value[idx]

  if (cell.type === 'monster') {
    monster.value = { ...cell.data, hp: cell.data.hp }
    inCombat.value = true
    return
  }

  if (cell.type === 'item') {
    currentItem.value = cell.data
    showItemPanel.value = true
    return
  }

  posX.value = nx
  posY.value = ny
}

function attack() {
  monster.value.hp -= character.value.power
  if (monster.value.hp <= 0) {
    inCombat.value = false
    removeCurrentCellContent()
    return
  }
  character.value.hp -= monster.value.power
  if (character.value.hp <= 0) destroyCharacter()
}

function flee() {
  inCombat.value = false
}

function destroyCharacter() {
  fetch('/character', {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      Accept: 'application/json'
    },
    credentials: 'same-origin'
  }).then(res => {
    if (res.ok) {
      character.value = null
    } else {
      window.location.href = '/dashboard'    

    }
  })
}

function removeCurrentCellContent() {
  const idx = currentIndex.value
  const row = Math.floor(idx / 5)
  const col = idx % 5
  mapRef.value[row][col] = { type: 'empty', data: null }
}

function equipItem(item) {
  equippedItems.value.push(item)
  removeCurrentCellContent()
  closeItemPanel()
}

function discardItem() {
  removeCurrentCellContent()
  closeItemPanel()
}

function closeItemPanel() {
  showItemPanel.value = false
  currentItem.value = null
}
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600;
}
.btn-red {
  @apply bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600;
}
.btn-gray {
  @apply bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400;
}
.btn-red-outline {
  @apply border border-red-500 text-red-500 px-4 py-2 rounded hover:bg-red-50;
}
.move-btn {
  @apply px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-lg font-bold;
}
</style>
