<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('companies')->insert([
                'short_name' => "SISI",
                'long_name' => "PT Sinergi Informatika Semen Indonesia",
                'logo' => 'http://127.0.0.1:8000/images/pt.sisi.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('companies')->insert([
                'short_name' => "SBI",
                'long_name' => "PT Solusi Bangun Indonesia",
                'logo' => 'http://127.0.0.1:8000/images/pt.sbi.jpeg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('companies')->insert([
                'short_name' => "UTSG",
                'long_name' => "PT United Tractors Semen Gresik",
                'logo' => 'http://127.0.0.1:8000/images/pt.utsg.jpeg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('companies')->insert([
                'short_name' => "PP",
                'long_name' => "PT Pembangunan Perumahan",
                'logo' => 'http://127.0.0.1:8000/images/ptpp.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
            DB::table('companies')->insert([
                'short_name' => "ADHI",
                'long_name' => "PT Adhi Karya",
                'logo' => 'http://127.0.0.1:8000/images/adhi.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null, 
            ]);
    }
}
