<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $video;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->video = Video::factory()->create();
    }

    #[Test]
    public function it_stores_a_new_comment()
    {
        $commentData = Comment::factory()->make(['video_id' => $this->video->id])->toArray();

        $response = $this->postJson(route('comments.store'), $commentData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'New record created successfully.']);

        $this->assertDatabaseHas('comments', $commentData);
    }

    #[Test]
    public function it_updates_a_comment()
    {
        $comment = Comment::factory()->create(['video_id' => $this->video->id]);
        $updateData = ['content' => 'Updated content'];

        $response = $this->putJson(route('comments.update', $comment->id), $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Comment updated successfully.']);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'content' => 'Updated content', 'is_edited' => true]);
    }

    #[Test]
    public function it_deletes_a_comment()
    {
        $comment = Comment::factory()->create(['video_id' => $this->video->id]);

        $response = $this->deleteJson(route('comments.delete', $comment->id));

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Comment deleted successfully.']);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    #[Test]
    public function it_lists_comments_for_a_video()
    {
        Comment::factory()->count(3)->create(['video_id' => $this->video->id]);

        $response = $this->getJson(route('comments.list', $this->video->id));

        $response->assertStatus(200)
                 ->assertJson(['message' => '3 comments found', 'total' => 3])
                 ->assertJsonCount(3, 'data');
                 ;
    }

    #[Test]
    public function it_fetches_top_comments_for_a_video()
    {
        Comment::factory()->count(5)->create(['video_id' => $this->video->id,'parent_comment_id'=>NULL]);

        $response = $this->getJson(route('comments.topItems', $this->video->id));

        $response->assertStatus(200)
                 ->assertJson(['message' => '5 comments found', 'total' => 5])
                 ->assertJsonCount(5, 'data');
    }

    #[Test]
    public function it_returns_error_when_video_not_found()
    {
        $response = $this->getJson(route('comments.list', 999));

        $response->assertStatus(404)
                 ->assertJson(['error' => 'Video not found']);
    }
}
