<?php

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function ($u) {
            $u->applications()->saveMany(factory(Application::class,10)->make());
            $u->assignRole(User::ROLE_USER);
        });
    }
}
