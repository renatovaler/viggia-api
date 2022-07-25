<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User\Models\User::factory(50)->create();
        
        $userSystemRoles = config('roles.all_user_system_roles');
        foreach($userSystemRoles as $key => $role) {
            \App\Role\Models\Role::create([
                'name' => $role,
                'description' => 'Default '. $role
            ]);
        }
    }
}
