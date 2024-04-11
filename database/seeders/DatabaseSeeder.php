<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\IncomeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password'=> 'user1'            
        ]);

        User::factory()->create([
            'name' => 'user2',
            'email' => 'user2@example.com',
            'password'=> 'user2'            
        ]);

        // $this->call(IncomeSeeder::class);
        // $this->call(ExpenseSeeder::class);
    }
}
