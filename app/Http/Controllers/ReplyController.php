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
        $record = Comment::with(['receiver', 'reactionsCount'])->find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));
        try {
            $replies = $record->replies()
                ->with(['receiver', 'reactionsCount'])
                ->latest()
                ->paginate(10);

            return response()->json([
                "message" => "Comment and replies",
                "comment" => $record,
                "replies" => $replies->items(),
                "total_replies" => $replies->total(),
                "current_page" => $replies->currentPage(),
                "last_page" => $replies->lastPage(),
                "per_page" => $replies->perPage(),
                "next_page_url" => $replies->nextPageUrl(),
                "prev_page_url" => $replies->previousPageUrl()
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => "Failed to find comments"], 500);
        }
    }


    /**
     * Get a list of replies for a comment.
     *
     * @param int $videoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function topReplies($commentId, Request $request)
    {
        Comment::find($commentId) ?? abort(response()->json(["error" => "Comment not found"], 404));
        try {
            $trendingReplies = Comment::trendingReplies($commentId);
            return response()->json([
                "message" => "Top replies",
                'data' => $trendingReplies->items(),
                'total' => $trendingReplies->total(),
                'current_page' => $trendingReplies->currentPage(),
                'last_page' => $trendingReplies->lastPage(),
                'per_page' => $trendingReplies->perPage(),
                'next_page_url' => $trendingReplies->nextPageUrl(),
                'prev_page_url' => $trendingReplies->previousPageUrl()
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => "Failed to find comments"], 500);
        }
    }
}
