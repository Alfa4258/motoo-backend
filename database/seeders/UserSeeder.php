<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        DB::table('users')->insert([
            'name' => 'admin123',
            'role' => 'admin',
            'email' => 'admin123@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/riqi.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('admin12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
        //reporter
        DB::table('users')->insert([
            'name' => 'reporter123',
            'role' => 'reporter',
            'email' => 'reporter123@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/bogor.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('reporter12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
        
        DB::table('users')->insert([
            'name' => 'M Syuja Ramadhan',
            'role' => 'client',
            'email' => 'syuja@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/suja.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('user12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
        //teknisi
        DB::table('users')->insert([
            'name' => 'Utbah Ghazwan',
            'role' => 'teknisi',
            'email' => 'utbah@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/utbah.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('teknisi12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
        DB::table('users')->insert([
            'name' => 'Faisal A Hermawan',
            'role' => 'teknisi',
            'email' => 'faisal@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/bogor.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('teknisi12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
        DB::table('users')->insert([
            'name' => 'pino',
            'role' => 'client',
            'email' => 'pino@gmail.com',
            'photo' => 'http://127.0.0.1:8000/images/user/riqi.jpg',
            'phone' => '081299875369',
            'job' => 'wasd',
            'team' => 'a',
            'password' => Hash::make('user12345'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null, 
        ]);
    }
}
