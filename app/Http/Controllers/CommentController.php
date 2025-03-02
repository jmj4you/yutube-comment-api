<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;

/**
 * Class CommentController
 *
 * This controller handles all comment-related actions, such as creating, updating,
 * deleting comments, listing comments and listing TOP comments.
 */
class CommentController extends Controller
{
    /**
     * Store a new comment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();
        try {
            $newRecord = Comment::create($validated);
            return response()->json(['message' => 'New record created successfully.', 'data' => $newRecord], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save comment'], 500);
        }
    }

    /**
     * Update an existing comment.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update($id, UpdateCommentRequest $request)
    {
        $record = Comment::find($id) ?? abort(response()->json(["error" => "Comment not found"], 404));
        $data = $request->validated();
        $data['is_edited'] = true;

        try {
            $record->update($data);
            return response()->json(['message' => 'Comment updated successfully.', 'data' => $record], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update comment'], 500);
        }
    }

    /**
     * Delete a comment.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $record = Comment::find($id) ?? abort(response()->json(["error" => "Comment not found"], 404));
        try {
            $record->delete();
            return response()->json(['message' => 'Comment deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete comment'], 500);
        }
    }

    /**
     * Get a list of comments for a video.
     *
     * @param int $videoId
     * @return \Illuminate\Http\JsonResponse
     */

    public function list($videoId)
    {
        Video::find($videoId) ?? abort(response()->json(["error" => "Video not found"], 404));

        try {
            $records = Comment::with(['createdBy','receiver','reactionsCount', 'replies.reactionsCount'])
                ->where('video_id', $videoId)
                ->whereNull('parent_comment_id')
                ->latest()
                ->get();
            $count = $records->count();
            $message = match ($count) {
                0 => "No comment found",
                1 => "One comment found",
                default => $count . " comments found",
            };
            return response()->json(['message' => $message,'total' => $count, 'data' => $records], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to find comments'], 500);
        }
    }

    /**
     * Get a list of TOP comments for a video.
     *
     * @param int $videoId
     * @return \Illuminate\Http\JsonResponse
     */

    public function topItems($videoId, Request $request)
    {
        $video =Video::find($videoId) ?? abort(response()->json(["error" => "Video not found"], 404));
        try {
            $trendingComments = Comment::trendingComments($video->id);
            $count = $trendingComments->count();
            $message = match ($count) {
                0 => "No comment found",
                1 => "One comment found",
                default => $count . " comments found",
            };
            return response()->json(
                [
                    "message" => $message,
                    "total" => $count,
                    "data" => $trendingComments,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Failed to find comments"],
                500
            );
        }
    }
}
