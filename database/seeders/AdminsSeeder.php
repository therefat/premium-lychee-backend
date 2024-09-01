<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $password = bcrypt('admins');
        Admin::create([
            'email' => 'admin@admin.com',
            'password' => $password,
            'phone' => '0123456789',
            'role' => 'admin',
        ]);
    }
}
