<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ReactionController
 *
 * This controller handles all reaction-related actions, such as like, dislike,
 * and list the total counts of likes&dislikes for the comment.
 *
 */
class ReactionController extends Controller
{
    /**
     * Store a new reaction to the comment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReaction($commentId, $status, StoreReactionRequest $request)
    {
        $validated = $request->validated();
        $comment = Comment::find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));
        try {
            $statusNumber = $status === "like" ? 1 : -1;
            $exists = Reaction::where("comment_id", $comment->id)
                ->where("user_id", $validated['user_id'])
                ->first();

            $validated["comment_id"] = $comment->id;
            $validated["type"] = $statusNumber;
            $message = "";

            if ($exists) {
                $exists->delete();
                $message = ucfirst($status) . " removed successfully.";
            }

            if (!$exists || $exists->type !== $statusNumber) {
                $newRecord = Reaction::create($validated);
                $message = ucfirst($status) . " added successfully.";
            }

            return response()->json([
                "message" => $message,
                "data" => $newRecord ?? [],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "error" => "Failed to save reaction."
            ], 500);
        }
    }


    /**
     * Get the count of reactions.
     *
     * @param int $videoId
     * @return \Illuminate\Http\JsonResponse
     */

    public function reactions($commentId)
    {
        $comment = Comment::with("reactionsCount")->find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));
        try {
            return response()->json([
                "message" => "Reactions",
                "data" => $comment,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "error" => "Failed to find reactions"
            ], 500);
        }
    }
}
