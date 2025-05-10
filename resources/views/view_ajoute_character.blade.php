<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Créer mon personnage
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ url('/character') }}" method="POST">
          @csrf
          <input type="text" name="name" placeholder="Nom du personnage" required>
          <!-- Champs verrouillés pour race/classe -->
          <button type="submit">Créer</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
