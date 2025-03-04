<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoId =4;
        $commentId = 37;
        for ($i = 0; $i < 2; $i++) {
            $item = [
                'content' => fake()->sentence(),
                'user_id' => User::inRandomOrder()->first()->id,
                'video_id' => $videoId,
                'parent_comment_id' => $commentId,
            ];
            Comment::create($item);
            Reaction::create([
                "type" => 2, // reply
                "comment_id" => $commentId,
                "user_id" => $item['user_id'],
            ]);
        }

        $this->command->info($i.' reply added for comment_id =' . $item['parent_comment_id']);
    }
}
