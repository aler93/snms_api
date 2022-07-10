<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class createStatTest extends TestCase
{
    private function statNames()
    {
        return ["goals", "shots", "penalties", "passes"];
    }

    private function player()
    {
        return Player::all()->random();
    }

    private function stats(): array
    {
        $stats = [];

        for( $i = 0; $i < rand(1, 3); $i++) {
            $statName = $this->statNames();
            $stats[] = [
                "name" => $statName[rand(0, count($statName) -1 )],
                "value" => rand(1, 20)
            ];
        }

        return $stats;
    }

    /**
     * @return void
     */
    public function test_create_stat()
    {
        $payload = [
            "player_id" => $this->player()->id,
            "stats" => $this->stats()
        ];

        $response = $this->post('/api/stat', $payload);

        $response->assertStatus(202);
    }

    public function test_create_empty_stat()
    {
        $payload = [
            "player_id" => $this->player()->id,
            "stats" => []
        ];

        $response = $this->post('/api/stat', $payload);
        //$response->dump();

        $response->assertStatus(422);
    }

    public function test_create_invalid_player()
    {
        $payload = [
            "player_id" => -1,
            "stats" => $this->stats()
        ];

        $response = $this->post('/api/stat', $payload);
        //$response->dump();

        $response->assertStatus(422);
    }
}
