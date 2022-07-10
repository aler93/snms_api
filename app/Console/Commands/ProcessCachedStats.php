<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Stats;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ProcessCachedStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:process:cached';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse cached stats to DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $keys = Redis::keys("*player*");

        foreach($keys as $k) {
            $playerId= str_replace("laravel_database_", "", $k);
            $rows = json_decode(Redis::get(str_replace("laravel_database_", "", $k)), true);

            $playerId = explode(":", $playerId)[2];
            //dd(json_decode($data, true), $playerId);

            foreach( $rows as $row ) {
                $player = Stats::where("player_id", "=", $playerId)
                      ->where("name", "=", $row["name"])
                      ->first();

                if( is_null($player) ) {
                    $row = array_merge($row, ["player_id" => $playerId]);
                    $playerStat = new Stats($row);
                    $playerStat->save();

                    continue;
                }

                $player->value += $row["value"];
                if ($player->save() ) {
                    Redis::delete("stats:player:" . $playerId);
                }
            }
        }

        return 0;
    }
}
