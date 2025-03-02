<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $item = [
            'content' => fake()->sentence(),
            'user_id' => User::inRandomOrder()->first()->id,
            'video_id' => Video::inRandomOrder()->first()->id,
            'parent_comment_id' =>NULL,
        ];
        return $item;
    }
}
