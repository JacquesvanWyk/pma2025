<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FeedPost;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = FeedPost::where('author_type', 'App\Models\User')
            ->where('author_id', $user->id)
            ->where('is_approved', true)
            ->with(['reactions', 'comments.user'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total_posts' => FeedPost::where('author_type', 'App\Models\User')
                ->where('author_id', $user->id)
                ->where('is_approved', true)
                ->count(),
            'total_reactions' => $posts->sum(fn($post) => $post->reactions->count()),
            'total_comments' => $posts->sum(fn($post) => $post->comments->count()),
        ];

        return view('users.show', compact('user', 'posts', 'stats'));
    }
}
