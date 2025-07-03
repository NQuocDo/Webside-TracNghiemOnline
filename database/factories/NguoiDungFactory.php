<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NguoiDung>
 */
class NguoiDungFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'ho_ten' => $this->faker->name(),
            'mat_khau' => Hash::make('123456'),
            'vai_tro' => $this->faker->randomElement(['sinh_vien', 'giang_vien', 'truong_khoa']),
        ];
    }
}
