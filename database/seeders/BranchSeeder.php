<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::create([
            'name' => 'Headquarters',
            'address' => 'Lagos, Nigeria',
            'state_id' => 15,
            'is_administrative' => true,
        ]);

        User::create([
            'name' => 'System Administrator',
            'mobile' => '08183172770',
            'email' => 'admin@indigoafrica.net',
            'password' => Hash::make('pass1234'),
            'state_id' => 15,
            'role_id' => Role::SYSTEM_ADMIN,
            'branch_id' => $branch->id,
        ]);
    }
}
