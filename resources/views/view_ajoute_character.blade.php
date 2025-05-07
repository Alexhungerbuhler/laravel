<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Créer mon personnage
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('character.store') }}">
          @csrf

          {{-- Nom du personnage --}}
          <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium">Nom du personnage</label>
            <input
              type="text"
              name="name"
              id="name"
              value="{{ old('name') }}"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Entrez un nom unique"
            >
            @error('name')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Race (verrouillée) --}}
          <div class="mb-4">
            <label for="race" class="block text-gray-700 font-medium">Race</label>
            <input
              type="text"
              name="race"
              id="race"
              value="Humain"
              disabled
              class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md cursor-not-allowed"
            >
          </div>

          {{-- Classe (verrouillée) --}}
          <div class="mb-6">
            <label for="class" class="block text-gray-700 font-medium">Classe</label>
            <input
              type="text"
              name="class"
              id="class"
              value="Guerrier"
              disabled
              class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md cursor-not-allowed"
            >
          </div>

          {{-- Bouton de création --}}
          <div class="flex justify-end">
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Créer mon personnage
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
