<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition(): array
    {
        $status = $this->faker->boolean(20); // 20% đã đến
        return [
            'full_name'     => $this->faker->name(),
            'phone'         => '09' . $this->faker->numerify('########'),
            'status'        => $status,
            'number'        => $status ? strtoupper(Str::random(6)) : null,
            'qr_token'      => Str::uuid()->toString(),

            'invited_at'    => $this->faker->dateTimeBetween('-10 days', 'now'),
            'arrived_at'    => $status ? now() : null,

            'address'       => $this->faker->address(),
            'code_staft'    => $this->faker->regexify('[A-Z]{3}[0-9]{3}'),
            'custom_column3'=> $this->faker->word(),
            'custom_column4'=> $this->faker->word(),

            'department_id' => rand(1, 5), // tùy chỉnh theo dữ liệu thực tế
        ];
    }
}
