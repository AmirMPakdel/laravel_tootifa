<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<10;$i++){
            DB::table('tags')->insert([
                'title' => Str::random(4),
            ]);
        }

        for ($i=0;$i<7;$i++){
            DB::table('categories')->insert([
                'title' => Str::random(7),
            ]);
        }

//        for ($i=0;$i<4;$i++){
//            DB::table('level_three_groups')->insert([
//                'title' => Str::random(7),
//                'level_two_group_id' => 2,
//            ]);
//        }
//
//        for ($i=0;$i<2;$i++){
//            DB::table('level_three_groups')->insert([
//                'title' => Str::random(7),
//                'level_two_group_id' => 3,
//            ]);
//        }
//
//        for ($i=0;$i<3;$i++){
//            DB::table('level_three_groups')->insert([
//                'title' => Str::random(7),
//                'level_two_group_id' => 4,
//            ]);
//        }

    }
}
