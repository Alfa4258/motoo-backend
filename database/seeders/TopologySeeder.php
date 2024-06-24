<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TopologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            DB::table('topologies')->insert([
                'group' => 'Direct DB',
                'link' => 'https://' . str::random(10) . '.sig.id',
                'description' => Str::random(10),
                'status' => 'Not Use',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => null, 
            ]);
        }
        for ($i = 1; $i <= 2; $i++) {
            DB::table('topologies')->insert([
                'group' => 'API',
                'link' => 'https://' . str::random(10) . '.sig.id',
                'status' => 'In Use',
                'description' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_at' => null, 
            ]);
        }
    }
}
