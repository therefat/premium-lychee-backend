<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create([
            'name' => "admin"
        ]);
        //
        $password = bcrypt('admins');
        Admin::create([
            'email' => 'admin@admin.com',
            'name' => 'Admin',
            'password' => $password,
            'phone' => '0123456789',
            'role_id' => $role->id,
        ]);
    }
}
