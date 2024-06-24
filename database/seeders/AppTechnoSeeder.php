<?php

namespace Database\Seeders;

use App\Models\Technology;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppTechnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all Technologies and Apps
        $technologies = Technology::all();
        $apps = Application::all();
    
        // Iterate over Apps
        $apps->each(function ($app) use ($technologies) {
            // Get a random technology
            $randomTechnology = $technologies->random();
    
            // Attach the app with the selected technology
            $app->technologies()->attach($randomTechnology->id);
        });
    }
}
