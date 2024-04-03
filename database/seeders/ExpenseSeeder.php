<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expense::create(['user_id' => '9bb41db5-6965-426e-a7be-a773c8e1eaa7', 'amount' => 5000000, 'description' => 'Gajian', 'date' => '2024-04-01']);
    }
}
