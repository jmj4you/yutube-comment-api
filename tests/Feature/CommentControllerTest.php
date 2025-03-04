<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
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
            ->assertJsonCount(3, 'data');;
    }

    #[Test]
    public function it_fetches_top_comments_for_a_video()
    {
        Comment::factory()->count(5)->create(['video_id' => $this->video->id, 'parent_comment_id' => NULL]);

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
    #[Test]
    public function it_returns_empty_comments_message_when_no_comments_exist()
    {
        $response = $this->getJson(route('comments.list', $this->video->id));
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'No comment found',
                'total' => 0,
                'data' => []
            ]);
    }
    #[Test]
    public function it_returns_one_comment_message_and_data()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'video_id' => $this->video->id,
            'user_id' => $user->id,
            'parent_comment_id' => null,
        ]);

        $response = $this->getJson(route('comments.list', $this->video->id));
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'One comment found',
                'total' => 1,
                'data' => [
                    [
                        'id' => $comment->id,
                        'video_id' => $this->video->id,
                        'created_by' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ],
                        'receiver' => null,
                        'reactions_count' => 0
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_paginated_comments_with_correct_metadata()
    {
        Comment::factory()->count(15)->create([
            'video_id' => $this->video->id,
            'parent_comment_id' => null,
        ]);

        $response = $this->getJson(route('comments.list', $this->video->id));
        $response->assertStatus(200)
            ->assertJson([
                'total' => 15,
                'per_page' => 10,
                'current_page' => 1,
                'last_page' => 2,
            ])
            ->assertJsonStructure([
                'next_page_url',
                'prev_page_url' // null for first page
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    #[Test]
    public function it_orders_comments_by_latest_first()
    {
        $comment1 = Comment::factory()->create([
            'video_id' => $this->video->id,
            'parent_comment_id' => null,
            'created_at' => Carbon::now()->subDay(),
        ]);
        $comment2 = Comment::factory()->create([
            'video_id' => $this->video->id,
            'parent_comment_id' => null,
            'created_at' => Carbon::now(),
        ]);

        $response = $this->getJson(route('comments.list', $this->video->id));
        $data = $response->json('data');
        $this->assertEquals($comment2->id, $data[0]['id']);
        $this->assertEquals($comment1->id, $data[1]['id']);
    }

}
