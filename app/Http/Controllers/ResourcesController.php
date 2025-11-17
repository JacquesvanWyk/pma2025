<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Tract;

class ResourcesController extends Controller
{
    public function index()
    {
        return view('resources.index');
    }

    public function tracts()
    {
        $tracts = Tract::published()->orderBy('code')->get();
        $tractsByLanguage = $tracts->groupBy('language');

        return view('resources.tracts', compact('tracts', 'tractsByLanguage'));
    }

    public function ebooks()
    {
        $ebooks = Ebook::orderBy('id')->get();
        $featuredEbook = $ebooks->where('is_featured', true)->first();
        $ebooksByLanguage = $ebooks->groupBy('language');

        return view('resources.ebooks', compact('ebooks', 'featuredEbook', 'ebooksByLanguage'));
    }
}
