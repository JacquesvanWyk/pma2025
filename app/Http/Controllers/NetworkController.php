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
        if (! $networkMember->isApproved()) {
            abort(404);
        }

        return view('network.show', compact('networkMember'));
    }

    public function join()
    {
        // Redirect to login if not authenticated
        if (! auth()->check()) {
            return redirect()->route('login')->with('message', 'Please login or register to join the believer network.');
        }

        // If authenticated, redirect to dashboard
        return redirect()->route('dashboard');
    }

    public function registerIndividual()
    {
        return view('network.register', ['type' => 'individual']);
    }

    public function registerFellowship()
    {
        return view('network.register', ['type' => 'group']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:individual,group',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'total_believers' => 'nullable|integer|min:1',
            'household_members' => 'nullable|array',
            'household_members.*.name' => 'required|string|max:255',
            'show_household_members' => 'boolean',
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
            'user_id' => auth()->id() ?? null,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'total_believers' => $validated['total_believers'] ?? 1,
            'household_members' => $validated['household_members'] ?? null,
            'show_household_members' => $validated['show_household_members'] ?? false,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'] ?? null,
            'meeting_times' => $validated['meeting_times'] ?? null,
            'show_email' => $validated['show_email'] ?? true,
            'show_phone' => $validated['show_phone'] ?? false,
            'status' => 'pending',
        ]);

        // Attach languages
        if (! empty($validated['languages'])) {
            $networkMember->languages()->attach($validated['languages']);
        }

        return redirect()->route('network.index')
            ->with('success', 'Your network submission has been received! It will be reviewed by our team and you\'ll receive an email notification once approved.');
    }

    public function edit(NetworkMember $networkMember)
    {
        // Only allow editing own network member
        if ($networkMember->user_id !== auth()->id()) {
            abort(403);
        }

        return view('network.register', [
            'type' => $networkMember->type,
            'networkMember' => $networkMember,
        ]);
    }

    public function update(Request $request, NetworkMember $networkMember)
    {
        // Only allow updating own network member
        if ($networkMember->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:individual,group',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'total_believers' => 'nullable|integer|min:1',
            'household_members' => 'nullable|array',
            'household_members.*.name' => 'required|string|max:255',
            'show_household_members' => 'boolean',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'meeting_times' => 'nullable|string|max:255',
            'languages' => 'array',
            'languages.*' => 'exists:languages,id',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
        ]);

        $networkMember->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'total_believers' => $validated['total_believers'] ?? 1,
            'household_members' => $validated['household_members'] ?? null,
            'show_household_members' => $validated['show_household_members'] ?? false,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'] ?? null,
            'meeting_times' => $validated['meeting_times'] ?? null,
            'show_email' => $validated['show_email'] ?? true,
            'show_phone' => $validated['show_phone'] ?? false,
        ]);

        // Sync languages
        if (! empty($validated['languages'])) {
            $networkMember->languages()->sync($validated['languages']);
        } else {
            $networkMember->languages()->detach();
        }

        return redirect()->route('network.index')
            ->with('success', 'Your network profile has been updated successfully!');
    }
}
