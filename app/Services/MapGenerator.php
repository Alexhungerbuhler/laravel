<?php
// app/Services/MapGenerator.php
namespace App\Services;

class MapGenerator
{
    public static function generate(): array
    {
        // Chemins validés en Tinker
        $monstersFile = storage_path('app/data/monstres.json');
        $itemsFile    = storage_path('app/data/items.json');

        $monsters = is_file($monstersFile)
            ? json_decode(file_get_contents($monstersFile), true) ?: []
            : [];
        $items = is_file($itemsFile)
            ? json_decode(file_get_contents($itemsFile), true)['items'] ?? []
            : [];

        $map = [];
        for ($y = 0; $y < 5; $y++) {
            $row = [];
            for ($x = 0; $x < 5; $x++) {
                $r = rand(1, 100);
                if ($r <= 50 && count($monsters) > 0) {
                    $m = $monsters[array_rand($monsters)];
                    $row[] = ['type'=>'monster','data'=>$m];
                } elseif ($r <= 50 && count($items) > 0) {
                    $i = $items[array_rand($items)];
                    $row[] = ['type'=>'item','data'=>$i];
                } else {
                    $row[] = ['type'=>'empty','data'=>null];
                }
            }
            $map[] = $row;
        }

         // Place la case de sortie en bas-droite
         $map[4][4] = ['type'=>'exit','data'=>null];
        return $map;
    }
}
