<?php

namespace App\Http\Controllers;

use App\Models\Note;

class NoteController extends Controller
{
    public function index()
    {
        $notesByLanguage = Note::published()
            ->orderBy('title')
            ->get()
            ->groupBy('language');

        return view('resources.notes', compact('notesByLanguage'));
    }

    public function download(Note $note)
    {
        $note->incrementDownloadCount();

        return response()->download(
            storage_path('app/public/notes/'.$note->file_path),
            $note->title.'.'.$note->file_type
        );
    }
}
