<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleries = Gallery::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['tags', 'event', 'images'])
            ->withCount('images')
            ->latest('created_at')
            ->paginate(12);

        return view('galleries.index', compact('galleries'));
    }

    public function show(string $slug): View
    {
        $gallery = Gallery::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['tags', 'event', 'images' => fn ($q) => $q->orderBy('order_position')])
            ->firstOrFail();

        return view('galleries.show', compact('gallery'));
    }
}
