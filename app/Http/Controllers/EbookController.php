<?php

namespace App\Http\Controllers;

use App\Models\Ebook;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::query()
            ->latest()
            ->paginate(12);

        return view('ebooks.index', compact('ebooks'));
    }

    public function show($slug)
    {
        $ebook = Ebook::query()
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related books (same language, exclude current)
        $relatedEbooks = Ebook::query()
            ->where('language', $ebook->language)
            ->where('id', '!=', $ebook->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('ebooks.show', compact('ebook', 'relatedEbooks'));
    }

    public function download($slug)
    {
        $ebook = Ebook::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $ebook->increment('download_count');

        $filename = $ebook->pdf_file;
        $path = storage_path('app/public/ebooks/'.$filename);

        if (! file_exists($path)) {
            abort(404, 'Ebook file not found');
        }

        return response()->download($path, $filename);
    }
}
