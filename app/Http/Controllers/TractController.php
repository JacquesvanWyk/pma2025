<?php

namespace App\Http\Controllers;

use App\Models\Tract;

class TractController extends Controller
{
    public function index()
    {
        $tracts = Tract::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('tags')
            ->latest('published_at')
            ->paginate(12);

        return view('tracts.index', compact('tracts'));
    }

    public function show($slug)
    {
        $tract = Tract::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('tags')
            ->firstOrFail();

        return view('tracts.show', compact('tract'));
    }

    public function download($slug, $format = 'pdf')
    {
        $tract = Tract::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $tract->incrementDownloadCount();

        return match ($format) {
            'md', 'markdown' => $this->downloadMarkdown($tract),
            'html' => $this->downloadHtml($tract),
            'pdf' => $this->downloadPdf($tract),
            default => abort(404, 'Invalid format'),
        };
    }

    protected function downloadMarkdown(Tract $tract): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = \Illuminate\Support\Str::slug($tract->title).'-'.now()->format('Y-m-d').'.md';

        return response()->streamDownload(function () use ($tract) {
            echo $tract->content;
        }, $filename, [
            'Content-Type' => 'text/markdown',
        ]);
    }

    protected function downloadHtml(Tract $tract): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = \Illuminate\Support\Str::slug($tract->title).'-'.now()->format('Y-m-d').'.html';

        $html = view('tracts.download-html', compact('tract'))->render();

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'text/html',
        ]);
    }

    protected function downloadPdf(Tract $tract): mixed
    {
        $filename = \Illuminate\Support\Str::slug($tract->title).'-'.now()->format('Y-m-d').'.pdf';

        return \Spatie\LaravelPdf\Facades\Pdf::view('tracts.download-pdf', compact('tract'))
            ->format('a4')
            ->name($filename);
    }
}
