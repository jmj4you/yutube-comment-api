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
        return $this->hasOne(User::class,'id', "reply_to");
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
                SUM(CASE WHEN type = -1 THEN 1 ELSE 0 END) AS dislikes"
            )
            ->groupBy('comment_id');
    }

    public static function trendingComments($videoId)
    {
        return Comment::leftJoin('reactions', 'comments.id', '=', 'reactions.comment_id')
            ->leftJoin('comments AS replyComments', 'comments.id', '=', 'replyComments.parent_comment_id')
            ->selectRaw("
            SUM(reactions.type) + COUNT(replyComments.id) +
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
            ->with(["createdBy","reactionsCount", "replies.reactionsCount","replies.receiver"])
            ->get();
    }
}
