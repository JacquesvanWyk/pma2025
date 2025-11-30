<?php

namespace App\Http\Controllers;

use App\Models\Short;

class ShortController extends Controller
{
    public function index()
    {
        $shorts = Short::published()
            ->ordered()
            ->get();

        return view('shorts.index', [
            'shorts' => $shorts,
        ]);
    }

    public function show(Short $short)
    {
        if (! $short->is_published) {
            abort(404);
        }

        $short->incrementViewCount();

        $relatedShorts = Short::published()
            ->where('id', '!=', $short->id)
            ->ordered()
            ->limit(6)
            ->get();

        return view('shorts.show', [
            'short' => $short,
            'relatedShorts' => $relatedShorts,
        ]);
    }
}
