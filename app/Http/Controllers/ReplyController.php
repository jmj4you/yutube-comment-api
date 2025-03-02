<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ReplyController
 *
 * This controller handles all reply-related actions, such as reply to a comment,
 * and list the total replies for the comment.
 *
 */
class ReplyController extends Controller
{
    /**
     * Store a new reply for a comment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReply($commentId, StoreReplyRequest $request)
    {
        $validated = $request->validated();
        $comment = Comment::find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));

        try {
            $validated["video_id"] = $comment->video_id;
            $validated["parent_comment_id"] = $comment->id;
            $newRecord = Comment::create($validated);

            /**
             * Adding a reaction data for the reply.
             * Here we consider reply is more interested reaction than like/dislike
             */
            Reaction::create([
                "type" => 2, // reply
                "comment_id" => $comment->id,
                "user_id" => $validated['user_id'],
            ]);

            return response()->json([
                "message" => "Reply added successfully.",
                "data" => $newRecord,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Failed to save comment"], 500);
        }
    }


     /**
      * Get a list of replies for a comment.
      *
      * @param int $videoId
      * @return \Illuminate\Http\JsonResponse
      */
      public function replies($commentId, Request $request)
      {
          try {
              $record = Comment::with(["replies.receiver", 'replies.reactionsCount'])->find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));
              return response()->json([
                  "message" => "Comment and replies",
                  "data" => $record,
              ], 200);
          } catch (\Exception $e) {
            Log::error($e);
              return response()->json(["error" => "Failed to find comments"], 500);
          }
      }


}
