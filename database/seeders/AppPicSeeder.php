<?php

namespace Database\Seeders;

use App\Models\Pic;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppPicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define available pic types
        $picTypes = ['old_pic', 'first_pic', 'backup_pic', 'pic_ict','pic_user'];
    
        // Get all Pics and Apps
        $pics = Pic::all();
        $apps = Application::all();
    
        // Iterate over Apps
        $apps->each(function ($app) use ($pics, $picTypes) {
            // Shuffle pic types to ensure randomness
            shuffle($picTypes);
            
            // Iterate over pic types and attach each app with a pic
            foreach ($picTypes as $picType) {
                // Get a random pic
                $pic = $pics->random();
                
                // Attach the app with the selected pic type
                $pic->applications()->attach($app->id, ['pic_type' => $picType]);
            }
        });
    }
    
}
