<?php
// app/Http/Controllers/CharacterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    // Affiche le formulaire
    public function create()
    {
        return view('view_ajoute_character');
    }

    // Stocke le personnage (la map est générée via hook)
    public function store(Request $r)
    {
        $r->validate(['name'=>'required|string|max:255']);
        $user = $r->user();
        if ($user->character) {
            return redirect()->route('dashboard')
                             ->with('error','Personnage déjà existant');
        }

        $user->character()->create([
            'name'=>''.$r->name,
            'race'=>'humain',
            'class'=>'guerrier',
            'level'=>1,'xp'=>0,
            'hp'=>20,'max_hp'=>20,'armor'=>0,'power'=>8,
            'x'=>0,'y'=>0,'equipped_items'=>null
        ]);

        return redirect()->route('dashboard');
    }

    public function dashboard(Request $request)
    {
        $character = $request->user()->character()->with('map')->first(); // Récupérer le personnage
        return view('dashboard', [
            'character' => $character,
            'map' => $character->map, // Si vous avez besoin de la carte
            'flash' => session('flash') // Si vous avez des messages flash
        ]);
    }

    // Supprime le personnage et la map (cascade)
    public function destroy(Request $r)
    {
        $c = $r->user()->character;
        if ($c) { $c->delete(); }
        return redirect()->route('dashboard')
                         ->with('success','Personnage supprimé');
    }
}
