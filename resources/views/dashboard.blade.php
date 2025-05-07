<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Tableau de bord
    </h2>
  </x-slot>

  <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
    @if(session('success'))
      <div class="p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="p-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
      </div>
    @endif

    @unless($character)
      <div class="bg-white p-6 rounded shadow text-center">
        <p class="mb-4">Vous n’avez pas encore de personnage.</p>
        <a href="{{ route('character.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Créer mon personnage
        </a>
      </div>
    @else
      <div class="bg-white p-6 rounded shadow flex flex-col lg:flex-row gap-6">

        {{-- GAUCHE : MAP + sorties --}}
        <div class="flex-1 space-y-4">
          <h3 class="text-lg font-bold mb-2">Map 5×5</h3>
          <div id="map-grid" class="grid grid-cols-5 gap-1">
            @foreach($character->map->cells as $y => $row)
              @foreach($row as $x => $cell)
                <div 
                  class="
                    w-12 h-12 flex items-center justify-center border text-sm
                    {{ $x === $character->x && $y === $character->y ? 'bg-yellow-200' : '' }}
                  ">
                  @if($x === $character->x && $y === $character->y)
                    🙂
                  @elseif($cell['type']==='exit')
                    🚩
                  @else
                    @switch($cell['type'])
                      @case('monster') 🗡 @break
                      @case('item')    🎁 @break
                      @default         – 
                    @endswitch
                  @endif
                </div>
              @endforeach
            @endforeach
          </div>

          {{-- Boutons d'action 2×2 --}}
          <div class="grid grid-cols-2 gap-2 mt-4">
            <button id="btn-attaquer"
                    class="w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              Attaquer
            </button>
            <button id="btn-flee"
                    class="w-full px-3 py-2 bg-blue-400 text-white rounded hover:bg-blue-500">
              Fuir
            </button>
            <button id="btn-use-item"
                    class="w-full px-3 py-2 bg-green-400 text-white rounded hover:bg-green-500">
              Potion
            </button>
            <button id="btn-loot"
                    class="w-full px-3 py-2 bg-yellow-400 rounded hover:bg-yellow-500">
              Butin
            </button>
          </div>
        </div>

        {{-- DROITE : STATS & DÉPLACEMENT --}}
        <div class="w-full lg:w-1/3 space-y-4">
          <h3 class="text-lg font-bold mb-2">Votre personnage</h3>
          <ul class="space-y-1">
            <li><strong>Nom :</strong> {{ $character->name }}</li>
            <li><strong>Race :</strong> {{ $character->race }}</li>
            <li><strong>Classe :</strong> {{ $character->class }}</li>
            <li><strong>HP :</strong> {{ $character->hp }} / {{ $character->max_hp }}</li>
            <li><strong>Puissance :</strong> {{ $character->power }}</li>
            <li><strong>Armure :</strong> {{ $character->armor }}</li>
            <li><strong>Niveau :</strong> {{ $character->level }}</li>
            <li><strong>XP :</strong> {{ $character->xp }}</li>
          </ul>

          <form method="POST" action="{{ route('character.destroy') }}">
            @csrf @method('DELETE')
            <button type="submit"
                    class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                    onclick="return confirm('Supprimer ce personnage ?')">
              Supprimer mon personnage
            </button>
          </form>

          <div class="grid grid-cols-3 gap-2 mt-4">
            <div></div>
            <button id="btn-move-up"
                    class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">↑</button>
            <div></div>

            <button id="btn-move-left"
                    class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">←</button>
            <div></div>
            <button id="btn-move-right"
                    class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">→</button>

            <div></div>
            <button id="btn-move-down"
                    class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">↓</button>
            <div></div>
          </div>
        </div>
      </div>
    @endunless

  </div>

  {{-- Axios et contrôle JavaScript --}}
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
      axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

      let posX = {{ $character->x ?? 0 }};
      let posY = {{ $character->y ?? 0 }};
      const gridEl = document.getElementById('map-grid');

      const render = (cells) => {
        gridEl.innerHTML = '';
        cells.forEach((row,y) => {
          row.forEach((cell,x) => {
            const div = document.createElement('div');
            div.className = `
              w-12 h-12 flex items-center justify-center border text-sm
              ${x===posX&&y===posY?'bg-yellow-200':''}
            `;
            if (x===posX&&y===posY)                 div.textContent = '🙂';
            else if (cell.type==='exit')            div.textContent = '🚩';
            else if (cell.type==='monster')         div.textContent = '🗡';
            else if (cell.type==='item')            div.textContent = '🎁';
            else                                    div.textContent = '–';
            gridEl.appendChild(div);
          });
        });
      };

      const fetchMap = async () => {
        try {
          const res = await axios.get('/api/map');
          posX = res.data.x; posY = res.data.y;
          render(res.data.cells);
        } catch (e) {
          console.error(e);
        }
      };

      const move = async (dx,dy) => {
        const nx = posX+dx, ny = posY+dy;
        if (nx<0||nx>4||ny<0||ny>4) return;
        try {
          const res = await axios.post('/api/move',{x:nx,y:ny});
          posX = res.data.x; posY = res.data.y;
          render(res.data.cells);
          // si on atteint la sortie :
          if (res.data.cells[posY][posX].type==='exit') {
            alert('🎉 Félicitations, vous avez atteint la sortie !');
          }
        } catch (e) {
          console.error(e);
        }
      };

      document.getElementById('btn-move-up')   .onclick = () => move(0,-1);
      document.getElementById('btn-move-down') .onclick = () => move(0,1);
      document.getElementById('btn-move-left') .onclick = () => move(-1,0);
      document.getElementById('btn-move-right').onclick = () => move(1,0);

      // l'API /api/move gère exit côté serveur aussi, optionnel
      fetchMap();
    });
  </script>
</x-app-layout>
