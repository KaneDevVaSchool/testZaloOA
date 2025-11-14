<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'status' => false,
            'number' => null,
            'qr_token' => (string) Str::uuid(), // tạo QR token
            'invited_at' => now()->subDays(rand(0, 7)),
        ];
    }
}
