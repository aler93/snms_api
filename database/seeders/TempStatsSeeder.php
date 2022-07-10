<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TempStatsSeeder extends Seeder
{
    private function statNames()
    {
        return ["goals", "shots", "penalties", "passes"];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [];

        $players = Player::all()->toArray();

        for( $i = 0; $i < 500000; $i ++ ) {
            $statName = $this->statNames();
            $row = [
                "player_id" => $players[rand(0, count($players)-1)]["id"],
                "name" => $statName[rand(0, count($statName) -1 )],
                "value" => rand(1, 20)
            ];

            $rows[] = $row;
            if( count($rows) >= 5000 ) {
                DB::table("stats_temp")->insert($rows);
                $rows = [];
            }
        }
    }
}
