<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ReplyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('comments')->group(function () {
    Route::get('/{video_id}/top-comments', [CommentController::class, 'topItems'])->name('comments.topItems');
    Route::get('/{video_id}', [CommentController::class, 'list'])->name('comments.list');

    Route::post('/', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{id}', [CommentController::class, 'delete'])->name('comments.delete');

    Route::post('/{id}/replies', [ReplyController::class, 'storeReply'])->name('reply.store');
    Route::get('/{id}/replies', [ReplyController::class, 'replies'])->name('reply.replies');
    Route::get('/{id}/top-replies', [ReplyController::class, 'topReplies'])->name('reply.topReplies');

    Route::post('/{commentId}/reactions/{status}', [ReactionController::class, 'storeReaction'])->where('status', 'like|dislike')->name('reactions.store');
    Route::get('/{commentId}/reactions', [ReactionController::class, 'reactions'])->name('reactions.list');
    Route::get('/{commentId}/details', [CommentController::class, 'details'])->name('comments.details');
});
