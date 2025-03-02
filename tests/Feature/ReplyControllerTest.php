<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReplyControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_stores_a_reply_for_a_comment()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create();
        $comment = Comment::factory()->create();

        // $this->actingAs($user);

        $response = $this->postJson(route('reply.store', $comment->id), [
            'content' => 'This is a reply',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Reply added successfully.',
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a reply',
            'video_id' => $comment->video_id,
            'parent_comment_id' => $comment->id,
        ]);

        $this->assertDatabaseHas('reactions', [
            'type' => 2,
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_returns_error_when_comment_not_found()
    {
        $response = $this->postJson(route('reply.store', 9999), [
            'content' => 'This is a reply',
            'user_id' => 1,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Comment not found',
            ]);
    }

    #[Test]
    public function it_gets_replies_for_a_comment()
    {
        $comment = Comment::factory()->create();
        $reply = Comment::factory()->create(['parent_comment_id' => $comment->id]);

        $response = $this->getJson(route('reply.replies', $comment->id));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Comment and replies',
                'data' => [
                    'id' => $comment->id,
                    'replies' => [
                        ['id' => $reply->id],
                    ],
                ],
            ]);
    }
}
