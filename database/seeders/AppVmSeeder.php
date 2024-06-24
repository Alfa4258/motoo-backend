<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\VirtualMachine;
use Illuminate\Database\Seeder;

class AppVmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all Virtual Machines
        $vms = VirtualMachine::all();
        $apps = Application::all();
    
        // Define possible environments
        $environments = ['development', 'production', 'testing'];
    
        // Iterate over Applications
        $apps->each(function ($app) use ($vms, $environments) {
            // Get a random environment for the application
            $randomEnvironment = $environments[array_rand($environments)];
    
            // Get two random VMs
            $randomVms = $vms->random(2);
    
            // Attach the VMs with the selected app and the specific environment
            foreach ($randomVms as $randomVm) {
                $app->virtual_machines()->attach($randomVm->id, [
                    'environment' => $randomEnvironment
                ]);
            }
        });
    }
}
