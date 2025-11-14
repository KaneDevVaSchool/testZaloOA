<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Guest;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    protected $model = Guest::class;
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'status' => false,
            'qr_token' => (string) Str::uuid(),
            'invited_at' => now()->subDays(rand(1, 10)),
        ];
    }
}
