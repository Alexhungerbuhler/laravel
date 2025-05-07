<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MapGenerator;

class GameController extends Controller
{
    /**
     * Renvoie la position et la map du personnage.
     * GET /api/map
     */
    public function getMap(Request $request)
    {
        $c   = $request->user()->character()->with('map')->firstOrFail();
        return response()->json([
            'x'     => $c->x,
            'y'     => $c->y,
            'cells' => $c->map->cells,
            'hp'    => $c->hp,
            'max_hp'=> $c->max_hp,
        ]);
    }

    /**
     * Déplace le personnage vers une nouvelle case (x,y).
     * POST /api/move
     * Payload JSON : { x: int, y: int }
     */
    public function move(Request $r)
    {
        $r->validate([ 'x'=>'required|int|min:0|max:4', 'y'=>'required|int|min:0|max:4' ]);
        $c = $r->user()->character;
        $c->update(['x'=>$r->x,'y'=>$r->y]);
    
        // RECHARGE toute la map
        $cells = $c->map->cells;
    
        return response()->json([
          'status'=>'moved',
          'x'=>$c->x,
          'y'=>$c->y,
          'cells'=>$cells,
          'hp'=>$c->hp,
          'max_hp'=>$c->max_hp,
        ]);
    }

    /**
     * Lance un tour de combat si la case actuelle est un monstre.
     * POST /api/combat
     */
    public function combat(Request $r)
    {
        $c     = $r->user()->character;
        $map   = $c->map;
        $cells = $map->cells;
        $cell  = &$cells[$c->y][$c->x];

        if ($cell['type'] !== 'monster') {
            return response()->json(['error'=>'Pas de monstre ici'], 400);
        }

        $m = $cell['data'];

        // Échange de dégâts
        $m['hp'] -= $c->power;
        $c->hp   -= $m['power'];

        // Joueur mort
        if ($c->hp <= 0) {
            // reset
            $c->hp = $c->max_hp;
            $c->x = $c->y = 0;
            // regénère nouvelle map
            $map->cells = MapGenerator::generate();
            $map->save();
            $c->save();

            return response()->json(['status'=>'dead']);
        }

        // Monstre tué
        if ($m['hp'] <= 0) {
            // calcul du drop
            $dropped = null;
            foreach ($cell['data']['drops'] as $drop) {
                if (rand(1, 100) <= $drop['chance']) {
                    $dropped = $drop['item_id'];
                    break;
                }
            }
            // retire le monstre
            $cell = ['type'=>'empty','data'=>null];
            $map->cells = $cells;
            $map->save();
            $c->save();

            return response()->json([
                'status' => 'win',
                'drop'   => $dropped,
                'hp'     => $c->hp,
            ]);
        }

        // combat en cours
        $cell['data'] = $m;
        $map->cells   = $cells;
        $map->save();
        $c->save();

        return response()->json([
            'status'     => 'ongoing',
            'player_hp'  => $c->hp,
            'monster_hp' => $m['hp'],
        ]);
    }

    /**
     * Retourne le résultat du loot pour une case item.
     * POST /api/loot
     * Payload JSON : { accept: boolean }
     */
    public function loot(Request $r)
    {
        $c      = $r->user()->character;
        $accept = $r->boolean('accept');
        $cell   = null;

        // récupère la cellule
        $cells = $c->map->cells;
        $cell  = $cells[$c->y][$c->x] ?? null;

        if (!$cell || $cell['type'] !== 'item') {
            return response()->json(['error'=>'Pas d\'item ici'], 400);
        }

        $itemData = $cell['data'];

        // supprime l'item de la map
        $cells[$c->y][$c->x] = ['type'=>'empty','data'=>null];
        $c->map->cells = $cells;
        $c->map->save();

        // si accepté, on l’ajoute à l’inventaire
        if ($accept) {
            $inv = $c->equipped_items ?: [];
            $inv[] = $itemData;
            $c->equipped_items = $inv;
            $c->save();
        }

        return response()->json([
            'status' => $accept ? 'equipped' : 'discarded',
            'item'   => $itemData,
        ]);
    }

    /**
     * Utilise une potion si présente.
     * POST /api/use-item
     */
    public function useItem(Request $r)
    {
        $c   = $r->user()->character;
        $inv = $c->equipped_items ?: [];

        foreach ($inv as $i => $it) {
            if (($it['type'] ?? '') === 'consumable' && isset($it['health_restore'])) {
                $c->hp = min($c->max_hp, $c->hp + $it['health_restore']);
                array_splice($inv, $i, 1);
                $c->equipped_items = $inv;
                $c->save();

                return response()->json(['status'=>'healed','hp'=>$c->hp]);
            }
        }

        return response()->json(['error'=>'Pas de potion'], 400);
    }

    /**
     * Fuit le combat.
     * POST /api/flee
     */
    public function flee()
    {
        return response()->json(['status'=>'fled']);
    }

    /**
     * Recommence la partie (reset perso + nouvelle map).
     * POST /api/restart
     */
    public function restart(Request $r)
    {
        $c   = $r->user()->character;
        $map = $c->map;

        $c->update([
            'hp' => $c->max_hp,
            'x'  => 0,
            'y'  => 0,
        ]);

        $map->cells = MapGenerator::generate();
        $map->save();

        return response()->json(['status'=>'restarted']);
    }
}
