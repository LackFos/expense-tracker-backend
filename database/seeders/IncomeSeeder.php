<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Income::create(['user_id' => '9bb41db5-6965-426e-a7be-a773c8e1eaa7', 'amount' => 18000, 'description' => 'Makan siang', 'date' => '2024-04-01']);
    }
}
