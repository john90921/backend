<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\post;
use App\Models\comment;
use App\Models\reply;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\like>
 */
class likeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $likeable = $this->faker->randomElement(
            [
                post::inRandomOrder()->first(),
                comment::inRandomOrder()->first(),
                reply::inRandomOrder()->first(),
            ]
        );

        return [
            "user_id" =>$likeable->user_id ?? User::factory(),
            "likeable_id" => $likeable->id ?? post::factory(),
            "likeable_type" => $likeable ? get_class($likeable): Post::class,
        ];
    }
}
