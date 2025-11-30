<?php

namespace App\Http\Controllers;

use App\Models\FeedPost;
use App\Models\Fellowship;
use App\Models\Individual;
use App\Models\Ministry;
use App\Models\NetworkMember;
use App\Notifications\NetworkMemberApprovalNotification;
use App\Notifications\NewNetworkMemberRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class NetworkController extends Controller
{
    public function index()
    {
        $ministries = Ministry::where('is_approved', true)
            ->where('is_active', true)
            ->with(['feedPosts' => function ($query) {
                $query->where('is_approved', true)
                    ->with(['reactions', 'comments'])
                    ->latest()
                    ->limit(5);
            }])
            ->latest()
            ->get();

        $individuals = Individual::where('is_approved', true)
            ->where('is_active', true)
            ->with(['feedPosts' => function ($query) {
                $query->where('is_approved', true)
                    ->with(['reactions', 'comments'])
                    ->latest()
                    ->limit(5);
            }])
            ->latest()
            ->get();

        $fellowships = Fellowship::where('is_approved', true)
            ->where('is_active', true)
            ->with(['feedPosts' => function ($query) {
                $query->where('is_approved', true)
                    ->with(['reactions', 'comments'])
                    ->latest()
                    ->limit(5);
            }])
            ->latest()
            ->get();

        $feedPosts = FeedPost::with(['author', 'reactions', 'comments'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return view('network.index', compact('ministries', 'individuals', 'fellowships', 'feedPosts'));
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
        $individualProfile = auth()->user()->individualProfile;

        if ($individualProfile) {
            return redirect()->route('network.edit', $individualProfile);
        }

        return view('network.register', [
            'type' => 'individual',
            'onboarding' => request()->has('onboarding'),
        ]);
    }

    public function registerFellowship()
    {
        return view('network.register', [
            'type' => 'group',
            'onboarding' => request()->has('onboarding'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:individual,group',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:2048', // 2MB Max
            'professional_skills' => 'nullable|string|max:1000',
            'ministry_skills' => 'nullable|string|max:1000',
            'total_believers' => 'nullable|integer|min:1',
            'household_members' => 'nullable|array',
            'household_members.*.name' => 'required|string|max:255',
            'show_household_members' => 'boolean',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'meeting_times' => 'nullable|string|max:255',
            'languages' => 'array',
            'languages.*' => 'exists:languages,id',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'onboarding' => 'nullable|boolean',
        ]);

        $data = [
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
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'country' => $validated['country'] ?? null,
            'address' => $validated['address'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'meeting_times' => $validated['meeting_times'] ?? null,
            'show_email' => $validated['show_email'] ?? false,
            'show_phone' => $validated['show_phone'] ?? false,
            'status' => 'pending',
        ];

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('network', 'public');
        }

        // Handle Skills (Convert comma-separated string to array) - Individuals Only
        if ($validated['type'] === 'individual') {
            if ($request->filled('professional_skills')) {
                $data['professional_skills'] = array_map('trim', explode(',', $request->professional_skills));
            }
            if ($request->filled('ministry_skills')) {
                $data['ministry_skills'] = array_map('trim', explode(',', $request->ministry_skills));
            }
        } else {
            $data['professional_skills'] = null;
            $data['ministry_skills'] = null;
        }

        $networkMember = NetworkMember::create($data);

        // Attach languages
        if (! empty($validated['languages'])) {
            $networkMember->languages()->attach($validated['languages']);
        }

        // Notify admin about new registration
        Notification::route('mail', 'jvw679@gmail.com')
            ->notify(new NewNetworkMemberRegistered($networkMember));

        // Handle onboarding redirect
        if ($request->has('onboarding') && $request->onboarding) {
            if ($validated['type'] === 'individual') {
                return redirect()->route('onboarding', ['step' => 'fellowship'])
                    ->with('success', 'Individual profile created! Do you also want to register a fellowship?');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Onboarding complete! Welcome to the dashboard.');
        }

        return redirect()->route('dashboard')
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
            'image' => 'nullable|image|max:2048',
            'professional_skills' => 'nullable|string|max:1000',
            'ministry_skills' => 'nullable|string|max:1000',
            'total_believers' => 'nullable|integer|min:1',
            'household_members' => 'nullable|array',
            'household_members.*.name' => 'required|string|max:255',
            'show_household_members' => 'boolean',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'meeting_times' => 'nullable|string|max:255',
            'languages' => 'array',
            'languages.*' => 'exists:languages,id',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
        ]);

        $data = [
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
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'country' => $validated['country'] ?? null,
            'address' => $validated['address'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'meeting_times' => $validated['meeting_times'] ?? null,
            'show_email' => $validated['show_email'] ?? false,
            'show_phone' => $validated['show_phone'] ?? false,
        ];

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($networkMember->image_path) {
                Storage::disk('public')->delete($networkMember->image_path);
            }
            $data['image_path'] = $request->file('image')->store('network', 'public');
        }

        // Handle Skills - Individuals Only
        if ($validated['type'] === 'individual') {
            if ($request->filled('professional_skills')) {
                $data['professional_skills'] = array_map('trim', explode(',', $request->professional_skills));
            } else {
                $data['professional_skills'] = null;
            }

            if ($request->filled('ministry_skills')) {
                $data['ministry_skills'] = array_map('trim', explode(',', $request->ministry_skills));
            } else {
                $data['ministry_skills'] = null;
            }
        } else {
            // For groups, clear any existing skills just in case
            $data['professional_skills'] = null;
            $data['ministry_skills'] = null;
        }

        $networkMember->update($data);

        // Sync languages
        if (! empty($validated['languages'])) {
            $networkMember->languages()->sync($validated['languages']);
        } else {
            $networkMember->languages()->detach();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Your network profile has been updated successfully!');
    }

    public function quickApprove(NetworkMember $networkMember)
    {
        // Check if already approved
        if ($networkMember->status === 'approved') {
            return view('network.quick-approve-result', [
                'success' => false,
                'message' => 'This member has already been approved.',
                'networkMember' => $networkMember,
            ]);
        }

        // Approve the member
        $networkMember->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Send approval notification to the member
        if ($networkMember->user) {
            $networkMember->user->notify(new NetworkMemberApprovalNotification($networkMember));
        } else {
            Notification::route('mail', $networkMember->email)
                ->notify(new NetworkMemberApprovalNotification($networkMember));
        }

        return view('network.quick-approve-result', [
            'success' => true,
            'message' => 'Member approved successfully! They have been notified via email.',
            'networkMember' => $networkMember,
        ]);
    }
}
