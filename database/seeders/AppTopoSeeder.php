<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topology;

class AppTopoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all Topologies and Apps
        $topologies = Topology::all();
        $apps = Application::all();
    
        // Iterate over Apps
        $apps->each(function ($app) use ($topologies) {
            // Get a random topology
            $randomTopology = $topologies->random();
    
            // Attach the app with the selected topology
            $app->topologies()->attach($randomTopology->id);
        });
    }
}
