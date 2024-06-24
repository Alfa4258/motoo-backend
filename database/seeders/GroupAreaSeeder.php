<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('group_areas')->insert([
                'short_name' => "SIG",
                'long_name' => "PT Semen Indonesia",
                'image' => 'http://127.0.0.1:8000/images/sig.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('group_areas')->insert([
                'short_name' => "SG",
                'long_name' => "PT Semen Gresik",
                'image' => 'http://127.0.0.1:8000/images/semen-gresik.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('group_areas')->insert([
                'short_name' => "SP",
                'long_name' => "PT Semen Padang",
                'image' => 'http://127.0.0.1:8000/images/semen-padang.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('group_areas')->insert([
                'short_name' => "ST",
                'long_name' => "PT Semen Tonasa",
                'image' => 'http://127.0.0.1:8000/images/semen-tonasa.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
    }
}
