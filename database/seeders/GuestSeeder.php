<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 10 guest test
        Guest::factory()->count(30)->create();
    }
}
