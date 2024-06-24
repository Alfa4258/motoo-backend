<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Application;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VirtualMachineSeeder::class,
            TopologySeeder::class,
            TechnologySeeder::class,
            PicSeeder::class,
            GroupAreaSeeder::class,
            CompanySeeder::class,
            ApplicationSeeder::class,
            ReviewSeeder::class,
            AppVmSeeder::class,
            AppTopoSeeder::class,
            AppTechnoSeeder::class,
            AppPicSeeder::class,
        ]);
    }
}
