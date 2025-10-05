<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        User::factory()->withPersonalTeam()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'adminpass',
            'role' => 'admin'
        ]);

        User::factory()->withPersonalTeam()->create([
        'name' => 'agent',
        'email' => 'agent@example.com',
        'role' => 'agent', // <-- Set the role here
    ]);

        $user = User::factory(5)->withPersonalTeam()->create();
        $this->call([
            DepartmentSeeder::class,
            TicketSeeder::class,
            ReplySeeder::class,

        ]);
    }
}
