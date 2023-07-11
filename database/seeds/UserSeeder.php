<?php

use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
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
        $adminRole = Role::create(['name' => User::ROLE_ADMIN]);
        $managerRole = Role::create(['name' => User::ROLE_MANAGER]);
        $userRole = Role::create(['name' => User::ROLE_USER]);


        $admin = User::create([
            'name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);


        $subject = Subject::create([
            'name'=> 'test subject',
            'address' => 'test'
        ]);

        
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'subject_id'=>$subject->id
        ]);
        $admin->assignRole($adminRole);
        $manager->assignRole($managerRole);
        $user->assignRole($userRole);
    }
}
