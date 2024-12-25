<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //nama role
        $roleAdmin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'user']);

        // membuat user admin
        $userAdmin = \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@music.com', 'password' => bcrypt('admin123')]);

        // memberi role ke admin
        $userAdmin->assignRole($roleAdmin);
    }
}
