<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class
        ]);

        $admin = User::factory()->create([
            'name' => 'test User',
            'email' => 'test@test.dk',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole("superadmin");


        User::factory(10)->create()->each(function ($user) {
                $user->assignRole("manager");
        });

        $this->call([
            ProduktSeeder::class,
            OrderSeeder::class
            // Add other seeders here if needed
        ]);


       /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
