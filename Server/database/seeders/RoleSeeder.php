<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;  

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Utiliza el modelo Role para crear roles
        Role::create(['role_name' => 'Admin']);
        Role::create(['role_name' => 'User']);
        Role::create(['role_name' => 'Moderator']);
    }
}