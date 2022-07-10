<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $players = [];
        for( $i = 0; $i < 500; $i++ ) {
            $name = $faker->streetName;
            $nameShort = substr(explode(" ", $name)[1], 0, 3);

            $players[] = [
                "name"       => $name,
                "name_short" => strtoupper($nameShort),
                "birth_date" => $faker->dateTimeBetween('-45 years', '-5 years'),
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }

        DB::table("players")->insert($players);
    }
}
