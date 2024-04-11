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
            'id' => '9bb41db5-6965-426e-a7be-a773c8e1eaa7',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=> 'test'            
        ]);

        $this->call(IncomeSeeder::class);
        $this->call(ExpenseSeeder::class);
    }
}
