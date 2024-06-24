<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pic;
use App\Models\User;

class PicSeeder extends Seeder
{
    public function run()
    {

        // Dapatkan semua pengguna dengan peran teknisi atau client
        $users = User::whereIn('role', ['teknisi', 'client'])->get();

        foreach ($users as $user) {
            Pic::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'contact' => $user->phone,
                'photo' => $user->photo,
                'status' => 'excomunicado',
                'jobdesc' => 'farming',
            ]);
        }
    }
}

