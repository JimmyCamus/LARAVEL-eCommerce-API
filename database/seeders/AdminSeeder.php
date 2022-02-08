<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->name = 'name';
        $user->country = 'N/A';
        $user->state = 'N/A';
        $user->city = 'N/A';
        $user->address = 'N/A';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make('admin12');
        $user->rol = 1;
        $user->save();
    }
}
