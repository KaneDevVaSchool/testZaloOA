<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'department_name' => $this->faker->unique()->company(),
            'description'     => $this->faker->sentence(),
            'status'          => $this->faker->randomElement(['active', 'inactive', 'draft']),

            'custom_column'   => $this->faker->word(),
            'custom_column1'  => $this->faker->word(),
            'custom_column2'  => $this->faker->word(),
            'custom_column3'  => $this->faker->word(),
            'custom_column4'  => $this->faker->word(),
        ];
    }
}
