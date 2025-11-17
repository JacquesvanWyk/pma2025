<?php

namespace App\Http\Controllers;

use App\Models\Study;

class StudiesController extends Controller
{
    public function index()
    {
        return view('studies.index');
    }

    public function show($slug)
    {
        $study = Study::query()
            ->where('slug', $slug)
            ->published()
            ->with('tags')
            ->firstOrFail();

        return view('studies.show', compact('study'));
    }
}
