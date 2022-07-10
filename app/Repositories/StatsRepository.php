<?php

namespace App\Repositories;

use App\Models\Stats;
use App\Models\StatsTemp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class StatsRepository
{
    public function createNoCache(int $playerId, array $stats): bool
    {
        $rows = [];

        foreach( $stats as $stat ) {
            $rows[] = [
                "player_id" => $playerId,
                "name"      => $stat["name"],
                "value"     => $stat["value"],
            ];
        }

        return DB::table("stats_temp")->insert($rows);
    }

    public function all(int $limit = 100, int $offset = 0): array
    {
        $stats = Stats::orderBy("player_id", "desc")
                      ->limit($limit)
                      ->offset($offset)
                      ->get()
                      ->toArray();

        $playerIds = array_unique(array_column($stats, "player_id"));

        $output      = [];
        $playerStats = [];
        foreach( $stats as $stat ) {
            foreach( $playerIds as $id ) {
                if( $stat["player_id"] == $id ) {
                    $s = $stat;
                    unset($s["id"]);
                    unset($s["player_id"]);

                    $playerStats[$id][] = $s;
                }
            }
        }

        foreach( $playerStats as $k => $v ) {
            $output["standings"][] = [
                "player_id" => $k,
                "stats"     => $v
            ];
        }

        return $output;
    }

    public function allTemp(int $limit = 100, int $offset = 0): array
    {
        $output = [];

        $query = DB::select("
                    SELECT player_id, name, SUM(value) AS value FROM stats_temp
                    GROUP BY player_id, name
                    ORDER BY player_id DESC
                    LIMIT $limit OFFSET $offset"
        );

        $playerIds = array_unique(array_column($query, "player_id"));

        $playerStats = [];
        foreach( $query as $stat ) {
            $stat = (array) $stat;

            foreach( $playerIds as $id ) {
                if( $stat["player_id"] == $id ) {
                    $s = $stat;
                    unset($s["id"]);
                    unset($s["player_id"]);

                    $playerStats[$id][] = $s;
                }
            }
        }

        foreach( $playerStats as $k => $v ) {
            $output["standings"][] = [
                "player_id" => $k,
                "stats"     => $v
            ];
        }

        return $output;
    }

    public function createCache(int $playerId, array $stats)
    {
        $current = Redis::get("stats:player:" . $playerId);
        if( !is_null($current) ) {
            $cachedStat = json_decode($current, true);

            $stats = array_merge($stats, $cachedStat);
        }

        Redis::set("stats:player:" . $playerId, json_encode($stats));
    }

    public function getCached(int $playerId): array
    {
        $res = json_decode(Redis::get("stats:player:" . $playerId));

        return $res ?? [];
    }

    public function getAllCached(): array
    {
        $output = Redis::scan("*stats*");

        return $output ? $output : [];
    }
}
