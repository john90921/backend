<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\post;
use App\Models\comment;
use App\Models\reply;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\report>
 */
class reportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $reportable = $this->faker->randomElement(
            [
                post::inRandomOrder()->first(),
                comment::inRandomOrder()->first(),
                reply::inRandomOrder()->first(),
            ]
        );

        return [
            "user_id" =>$reportable->user_id ?? User::factory(),
            "reportable_id" => $reportable->id ?? post::factory(),
            "reportable_type" => $reportable ? get_class( $reportable): Post::class,
            "reason" => $this->faker->sentence(),
            "resolved" => $this->faker->boolean(),
        ];
    }
}
