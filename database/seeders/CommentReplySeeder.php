<?php

namespace Database\Seeders;

use App\Models\Comment;
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
        $item = [
            'content' => fake()->sentence(),
            'user_id' => User::inRandomOrder()->first()->id,
            'video_id' => Video::inRandomOrder()->first()->id,
            'parent_comment_id' => NULL,
        ];

        /**
         * parent_comment_id of the video_id and the comment's video_id should be same.
         *
         * Check by querry
         *
            SELECT
                t1.id,
                t2.id AS t2id,
                t1.video_id,
                t2.video_id AS t2video_id,
                t1.parent_comment_id,
                t2.parent_comment_id AS t2parent_comment_id
            FROM
                `comments` t1
            INNER JOIN `comments` t2 ON
                t1.id = t2.parent_comment_id
            ORDER BY
                t1.id;
         */
        $item['parent_comment_id'] = Comment::inRandomOrder()
            ->where('video_id', $item['video_id'])
            ->whereNull('parent_comment_id')
            ->first()->id ?? NULL;

        Comment::create($item);
        $this->command->info('One reply added for comment_id ='.$item['parent_comment_id']);

    }
}
