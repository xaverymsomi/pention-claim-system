<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        User::create([
            'name' => 'Coop Admin',
            'email' => 'admin@nssf.go.tz',
            'password' => Hash::make('admin123'),
            'user_type_id' => 1 // assuming 3 = cooperative
        ]);
    }
}
