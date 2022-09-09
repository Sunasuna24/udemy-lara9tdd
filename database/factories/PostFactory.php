<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            // 上記の記法は下記の記法のショートカット
            // 'user_id' => function () {
            //     return User::factory()->create()->id;
            // },
            'status' => Post::PUBLISHED,
            'title' => $this->faker->realText(20),
            'body' => $this->faker->realText(200)
        ];
    }

    public function randomStatus()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement([Post::CLOSED, Post::PUBLISHED, Post::PUBLISHED, Post::PUBLISHED, Post::PUBLISHED])
            ];
        });
    }

    public function statusClosed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Post::CLOSED
            ];
        });
    }
}
