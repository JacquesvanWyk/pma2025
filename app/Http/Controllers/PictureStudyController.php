<?php

namespace App\Http\Controllers;

use App\Models\PictureStudy;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PictureStudyController extends Controller
{
    public function index(Request $request): View
    {
        $query = PictureStudy::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with('tags');

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $request->tag));
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $pictureStudies = $query->latest('published_at')->paginate(12);

        $tags = Tag::query()
            ->whereHas('pictureStudies', fn ($q) => $q->where('status', 'published'))
            ->orderBy('name')
            ->get();

        return view('picture-studies.index', compact('pictureStudies', 'tags'));
    }
}
