<?php

namespace App\Console\Commands;

use App\Models\Stats;
use App\Models\StatsTemp;
use Illuminate\Console\Command;

class ProcessStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse temporary stats';

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
        $temp = StatsTemp::selectRaw("id, player_id, name, value")
                          ->where("computed", "=", false)
                          ->limit(1000)
                          ->get();

        $ids = [];
        foreach( $temp->toArray() as $row ) {
            $ids[] = $row["id"];

            $player = Stats::where("player_id", "=", $row["player_id"])
                           ->where("name", "=", $row["name"])
                           ->first();

            if( is_null($player) ) {
                $playerStat = new Stats($row);
                $playerStat->save();

                continue;
            }

            $player->value += $row["value"];
            $player->save();
        }

        StatsTemp::whereIn("id", $ids)->update(["computed" => 1]);

        return 0;
    }
}
