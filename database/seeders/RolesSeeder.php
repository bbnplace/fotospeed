<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Customer'
            ],
            [
                'id' => 2,
                'name' => 'Reception'
            ],
            [
                'id' => 3,
                'name' => 'Production'
            ],
            [
                'id' => 4,
                'name' => 'Management'
            ],
            [
                'id' => 5,
                'name' => 'Administrator'
            ],
            [
                'id' => 6,
                'name' => 'System Admin'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
