<template>
  <NavBar />

  <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div v-if="!character" class="bg-white p-6 rounded shadow text-center">
      <p class="mb-4">Vous n'avez pas encore de personnage.</p>
      <router-link to="/character/create" class="btn-primary">Créer mon personnage</router-link>
    </div>

    <div v-else class="bg-white p-6 rounded shadow flex flex-col lg:flex-row gap-6">
      <!-- GAUCHE : Map -->
      <div class="flex-1 space-y-4">
        <h3 class="text-center font-bold">Donjon du Valhallah</h3>
        <div class="grid grid-cols-5 gap-1">
          <div
            v-for="(cell, idx) in cellsList"
            :key="idx"
            :class="['w-12 h-12 flex items-center justify-center border text-sm transition',
                     idx === currentIndex ? 'bg-yellow-200' : 'bg-white']"
                     >
            <template v-if="idx === currentIndex">🙂</template>
            <tempalte v-if="cell.type === 'exit'">🚩</tempalte>
            <template v-else-if="mapRevealed || revealCell(idx)">
              <span v-if="cell.type === 'exit'">🚩</span>
              <span v-else-if="cell.type === 'monster'">🗡</span>
              <span v-else-if="cell.type === 'item'">🎁</span>
              <span v-else></span>
            </template>
            <template v-else></template>
          </div>
        </div>

        <!-- Déplacements -->
        <div class="grid grid-cols-3 gap-2 mt-4">
          <div></div>
          <button @click="move(0, -1)" class="move-btn">↑</button>
          <div></div>
          <button @click="move(-1, 0)" class="move-btn">←</button>
          <div></div>
          <button @click="move(1, 0)" class="move-btn">→</button>
          <div></div>
          <button @click="move(0, 1)" class="move-btn">↓</button>
          <div></div>
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

        <!-- Victoire -->
        <div v-if="hasWon" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white p-4 rounded shadow space-y-2">
            <h2 class="text-xl font-bold">🎉 Victoire !</h2>
            <p>Vous avez atteint la sortie. Félicitations !</p>
            <button @click="destroyCharacter" class="btn-red-outline w-full">Recommencer l'aventure</button>
          </div>
        </div>

        <!-- Loot -->
        <div v-if="showItemPanel" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white p-4 rounded shadow space-y-2">
            <h4 class="font-bold">Objet : {{ currentItem.name }}</h4>
            <p>Type : {{ currentItem.type }}</p>
            <ul>
              <li v-if="currentItem.hp">Bonus : + {{ currentItem.hp }} HP</li>
              <li v-if="currentItem.armor">Bonus : + {{ currentItem.armor }} Armure</li>
              <li v-if="currentItem.power">Bonus : + {{ currentItem.power }} Puissance</li>
              <li v-if="currentItem.hp_max">Bonus : + {{ currentItem.hp_max }} HP max</li>
            </ul>
            <div class="flex gap-2 justify-end">
              <button @click="equipItem(currentItem)" class="btn-red">Équiper</button>
              <button @click="discardItem()" class="btn-gray">Jeter</button>
              <button @click="closeItemPanel" class="btn-gray">Annuler</button>
            </div>
          </div>
        </div>
      </div>


      <hero />

      <!-- DROITE : Stats -->
      <div class="w-full lg:w-1/3 space-y-4">
        <h3 class="text-center font-bold">Votre personnage</h3>
        <ul class="text-sm space-y-1">
          <li><strong>Nom :</strong> {{ character.name }}</li>
          <li><strong>HP :</strong> {{ character.hp }} / {{ character.max_hp }}</li>
          <li><strong>Puissance :</strong> {{ character.power }}</li>
          <li><strong>Armure :</strong> {{ character.armor }}</li>
          <li><strong>Niveau :</strong> {{ character.level }}</li>
          <li><strong>XP :</strong> {{ character.xp }}</li>
        </ul>

        <div>
          <h4 class="mt-4 font-semibold text-sm">Équipement :</h4>
          <ul class="list-disc list-inside text-xs" v-if="equipped.length">
            <li v-for="item in equipped" :key="item.id">{{ item.name }} ({{ item.type }} + {{ item.armor }} {{ item.power }} {{ item.hp }} {{ item.hp_max }})</li>
          </ul>
          <p v-else class="text-xs text-gray-500">Aucun objet équipé</p>
        </div>

        <button @click="destroyCharacter" class="btn-red-outline w-full">
          Supprimer mon personnage
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import NavBar from '@/components/NavBar.vue'

function saveGame() {
  localStorage.setItem('game', JSON.stringify({
    character: character.value,
    map: mapRef.value,
    x: posX.value,
    y: posY.value,
    equipped: equipped.value,
    mapRevealed: mapRevealed.value
  }))
}

function loadGame() {
  const saved = JSON.parse(localStorage.getItem('game'))
  if (!saved) return

  character.value = saved.character || character.value
  mapRef.value = saved.map || mapRef.value
  posX.value = saved.x ?? posX.value
  posY.value = saved.y ?? posY.value
  equipped.value = saved.equipped || []
  mapRevealed.value = saved.mapRevealed || false
}

function clearSave() {
  localStorage.removeItem('game')
}

// Payload Blade
const { character: initChar, map = [] } = window.APP_PAYLOAD || {}
const character = ref({ ...initChar })
const mapRef = ref(Array.isArray(map) ? map : [])
const equipped = ref([])

const posX = ref(initChar?.x || 0)
const posY = ref(initChar?.y || 0)

const inCombat = ref(false)
const monster = ref({})
const showItemPanel = ref(false)
const currentItem = ref(null)

const cellsList = computed(() => mapRef.value.flatMap(row => row))
const currentIndex = computed(() => posY.value * 5 + posX.value)
const destination = {row: 0, col: 0};

const mapRevealed = ref(false)

const hasWon = ref(false)

function move(dx, dy) {
  const nx = posX.value + dx
  const ny = posY.value + dy
  
  if (nx < 0 || nx > 4 || ny < 0 || ny > 4) return
  destination.row = nx;
  destination.col = ny;
  tryMoveCell(ny * 5 + nx)
  saveGame()
}

function revealCell(idx) {
  return idx === currentIndex.value
}

function handleKeydown(e) {
  if (e.key === 'ArrowUp') move(0, -1)
  if (e.key === 'ArrowDown') move(0, 1)
  if (e.key === 'ArrowLeft') move(-1, 0)
  if (e.key === 'ArrowRight') move(1, 0)
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
  loadGame()
})
onBeforeUnmount(() => window.removeEventListener('keydown', handleKeydown))

function tryMoveCell(idx) {
  if (inCombat.value || hasWon.value) return

  const nx = idx % 5
  const ny = Math.floor(idx / 5)
  const cell = cellsList.value[idx]

  // Révélation de la carte si on atteint la sortie
  if (cell.type === 'exit') {
    mapRevealed.value = true
    hasWon.value = true
    clearSave()
  }

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
    removeCurrentCell()
    posX.value = currentIndex.value % 5
    posY.value = Math.floor(currentIndex.value / 5)

    if (monster.value.drops?.length) {
      const item = monster.value.drops[0]  // simple : 1 drop
      currentItem.value = item
      showItemPanel.value = true
    }

    return
  }

  const effectiveDamage = Math.max(monster.value.power - character.value.armor, 0)
  character.value.hp -= effectiveDamage

  if (character.value.hp <= 0) {
    //window.location.href = '/delete'
    destroyCharacter();
    clearSave()
    window.location.href = '/dashboard'    
  } 
  saveGame()
}

function equipItem(item) {
  if (item.hp_max) {
    character.value.max_hp += item.hp_max
  }

  if (item.hp) {
    character.value.hp += item.hp
    character.value.hp = Math.min(character.value.hp, character.value.max_hp)
  }

  if (item.power) character.value.power += item.power
  if (item.armor) character.value.armor += item.armor

  equipped.value.push(item)
  removeCurrentCell()
  closeItemPanel()
  saveGame()
}

function discardItem() {
  removeCurrentCell()
  closeItemPanel()
  saveGame()
}

function closeItemPanel() {
  showItemPanel.value = false
  currentItem.value = null
}

function removeCurrentCell() {
  mapRef.value[destination.col][destination.row] = { type: 'empty', data: null }
}

function flee() {
  inCombat.value = false
  saveGame()
}

//je n'arrive pas a supprimer le personnage avec <a href="/character/delete" /a> 
// qui normalement fonctionne 
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
      clearSave()
      window.location.href = '/dashboard'
      throw new Error('YOU DIED !')
    }
  }).catch(err => alert(err.message))
}

</script>

<style scoped>

.cell {
  pointer-events: none;
}

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
