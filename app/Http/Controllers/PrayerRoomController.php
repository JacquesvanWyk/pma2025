<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\PrayerRoomSession;
use App\Notifications\NewPrayerRequestNotification;
use App\Rules\ValidEmailDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PrayerRoomController extends Controller
{
    public function index()
    {
        $upcomingSession = PrayerRoomSession::upcoming()
            ->orderBy('session_date')
            ->first();

        $previousSession = PrayerRoomSession::past()
            ->orderBy('session_date', 'desc')
            ->first();

        return view('prayer-room.index', compact('upcomingSession', 'previousSession'));
    }

    public function store(Request $request)
    {
        // Silent rejection if honeypot is filled (spam bot detected)
        if ($request->filled('website')) {
            return redirect()->route('prayer-room.index')
                ->with('success', 'Your prayer request has been submitted. We will be praying for you!');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-]+$/u'],
            'email' => ['nullable', 'email', 'max:255', new ValidEmailDomain],
            'phone' => ['nullable', 'string', 'max:20'],
            'request' => ['required', 'string', 'min:10'],
            'is_private' => ['nullable', 'boolean'],
        ]);

        $prayerRequest = PrayerRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'request' => $validated['request'],
            'is_private' => $validated['is_private'] ?? false,
            'status' => 'pending',
        ]);

        // Send email notification
        Notification::route('mail', 'prayers@pioneermissionsafrica.co.za')
            ->notify(new NewPrayerRequestNotification($prayerRequest));

        // Mark as emailed
        $prayerRequest->update(['emailed' => true]);

        return redirect()->route('prayer-room.index')
            ->with('success', 'Your prayer request has been submitted. We will be praying for you!');
    }
}
