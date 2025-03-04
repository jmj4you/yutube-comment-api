<?php

namespace App\Models;

use App\Traits\ModelExtTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, ModelExtTrait;

    protected $fillable = ['is_edited', 'content', 'video_id', 'user_id', 'parent_comment_id', 'reply_to'];
    protected $casts = [
        'is_edited' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, "user_id", 'id');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'id', "reply_to");
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id', 'id')->orderBy('updated_at', 'DESC');
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'comment_id', 'id')->orderBy('updated_at', 'DESC');
    }

    public function reactionsCount()
    {
        return $this->hasOne(Reaction::class, 'comment_id', 'id')
            ->selectRaw(
                "comment_id,
                SUM(CASE WHEN type = 1 THEN 1 ELSE 0 END) AS likes,
                SUM(CASE WHEN type = -1 THEN 1 ELSE 0 END) AS dislikes,
                SUM(CASE WHEN type = 2 THEN 1 ELSE 0 END) AS replies
                "
            )
            ->groupBy('comment_id');
    }
    /**
     * Get the trending comments for a given video.
     *
     * This function calculates a "trending score" for each top-level comment on a video
     * based on the sum of reactions, number of replies, recency of the comment,
     * and the recency of the latest reaction relative to the comment’s creation date.
     *
     * Trending score formula:
     * - Sum of reactions on the comment
     * - Plus the number of replies to the comment
     * - Plus the number of days remaining in a year since the comment was created (higher recency boosts score)
     * - Plus a weighted metric based on the time gap between the comment's creation and the most recent reaction
     *
     * @param int $videoId The ID of the video for which to fetch trending comments.
     * @param int $perPage The number of comments to return per page (default: 10).
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated list of trending comments.
     */
    public static function trendingComments($videoId, $perPage = 10)
    {
        return Comment::leftJoin('reactions', 'comments.id', '=', 'reactions.comment_id')
            ->leftJoin('comments AS replyComments', 'comments.id', '=', 'replyComments.parent_comment_id')
            ->selectRaw("
            COALESCE(SUM(reactions.type) + COUNT(replyComments.id),0) +
            (365 - DATEDIFF(CURDATE(), comments.created_at)) +
            COALESCE(
                CASE
                    WHEN DATEDIFF(MAX(reactions.created_at), comments.created_at) = 0 THEN 1
                    ELSE DATEDIFF(MAX(reactions.created_at), comments.created_at) / NULLIF(SUM(reactions.type), 0)
                END,
                1
            ) AS trendingScore,
            comments.*
        ")
            ->whereNull("comments.parent_comment_id")
            ->where("comments.video_id", $videoId)
            ->groupBy("comments.id")
            ->orderByDesc("trendingScore")
            ->orderByDesc("comments.created_at")
            ->with(["createdBy", "reactionsCount"])
            ->paginate($perPage);
    }

    /**
     * Get the trending replies for a given comment.
     *
     * This function calculates a "trending score" for each reply based on:
     * - The sum of reactions (likes, dislikes) on the reply (defaulting to 0 if none exist)
     * - The recency of the reply, measured by the number of days remaining in the year since its creation
     * - A weighted metric based on the time difference between the reply's creation and the most recent reaction,
     *   divided by the sum of reactions (if available)
     *
     * Additional Sorting:
     * - Replies are sorted by trending score in descending order
     * - If two replies have the same score, they are further sorted by creation date (most recent first)
     *
     *
     * @param int $commentId The ID of the comment whose replies are being fetched
     * @param int $perPage The number of replies to return per page (default: 10)
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated list of trending replies
     */

    public static function trendingReplies($commentId, $perPage = 10)
    {
        return Comment::leftJoin('reactions', 'comments.id', '=', 'reactions.comment_id')
            ->selectRaw("
             COALESCE(SUM(reactions.type),0) +
            (365 - DATEDIFF(CURDATE(), comments.created_at)) +
            COALESCE(
                CASE
                    WHEN DATEDIFF(MAX(reactions.created_at), comments.created_at) = 0 THEN 1
                    ELSE DATEDIFF(MAX(reactions.created_at), comments.created_at) / NULLIF(SUM(reactions.type), 0)
                END,
                1
            ) AS trendingScore,
            comments.*
        ")
            ->where("comments.parent_comment_id", $commentId)
            ->groupBy("comments.id")
            ->orderByDesc("trendingScore")
            ->orderByDesc("comments.created_at")
            ->with(["createdBy", "reactionsCount", "receiver"])
            ->paginate($perPage);
    }
}
