<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Reply;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $depts = Department::all();
        // $users = User::all()->random(5);


        // for($i = 0; $i<20;$i++){
        //     $user = $users->random();
        //     $dept = $depts->random();

        //     Ticket::factory()
        //         ->for($user)
        //         ->for($dept)
        //         ->has(Reply::factory()->count(rand(1,5)))
        //         ->create();
    

        // }

        Ticket::factory(15)
        ->for($depts->random())
        ->has(Reply::factory()->count(rand(1,4)))
        ->create();
        //
    }
}
