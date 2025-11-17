<?php

namespace App\Http\Controllers;

use App\Models\NetworkMember;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function index()
    {
        return view('network.index');
    }

    public function show(NetworkMember $networkMember)
    {
        // Only show approved members
        if (!$networkMember->isApproved()) {
            abort(404);
        }

        return view('network.show', compact('networkMember'));
    }

    public function join()
    {
        return view('network.join');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:individual,group',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'meeting_times' => 'nullable|string|max:255',
            'languages' => 'array',
            'languages.*' => 'exists:languages,id',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
        ]);

        $networkMember = NetworkMember::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'],
            'meeting_times' => $validated['meeting_times'],
            'show_email' => $validated['show_email'] ?? true,
            'show_phone' => $validated['show_phone'] ?? false,
            'status' => 'pending',
        ]);

        // Attach languages
        if (!empty($validated['languages'])) {
            $networkMember->languages()->attach($validated['languages']);
        }

        return redirect()->route('network.index')
            ->with('success', 'Your network submission has been received! It will be reviewed by our team and you\'ll receive an email notification once approved.');
    }
}
