<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role= Role::create(['name' =>User::ROLE_ADMIN]);
        Role::create(['name' =>User::ROLE_USER]);
        Role::create(['name' =>User::ROLE_MANAGER]);


        $user =User::create([
            'name' => 'Super-Admin',
            'email' =>'superadmin@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole($role);

    }
}

