<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'mobile' => '08183172770',
            'email' => 'admin@indigoafrica.net',
            'password' => Hash::make('pass1234'),
            'state_id' => 1,
            'role_id' => 6,
        ]);
    }
}
