<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $comment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Video::factory()->create();
        $this->comment = Comment::factory()->create();
    }

    #[Test]
    public function it_stores_a_like_reaction_for_a_comment()
    {
        $response = $this->actingAs($this->user)->postJson(route('reactions.store', [
            'commentId' => $this->comment->id,
            'status' => 'like',
        ]), ['user_id' => $this->user->id]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Like added successfully.']);

        $this->assertDatabaseHas('reactions', [
            'comment_id' => $this->comment->id,
            'user_id' => $this->user->id,
            'type' => 1,
        ]);
    }

    #[Test]
    public function it_removes_a_like_reaction_if_already_exists()
    {
        Reaction::factory()->create([
            'comment_id' => $this->comment->id,
            'user_id' => $this->user->id,
            'type' => 1,
        ]);

        $response = $this->actingAs($this->user)->postJson(route('reactions.store', [
            'commentId' => $this->comment->id,
            'status' => 'like',
        ]), ['user_id' => $this->user->id]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Like removed successfully.']);

        $this->assertDatabaseMissing('reactions', [
            'comment_id' => $this->comment->id,
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function it_returns_error_when_comment_not_found()
    {
        $response = $this->actingAs($this->user)->postJson(route('reactions.store', [
            'commentId' => 9999,
            'status' => 'like',
        ]), ['user_id' => $this->user->id]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Comment not found']);
    }
}
