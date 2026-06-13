@extends('layouts.public')

@section('title', 'Camp Meeting 2026 — Pioneer Missions Africa')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-16" style="background: var(--color-indigo);">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest mb-5 px-3 py-1.5 rounded-full" style="background: var(--color-ochre); color: white;">
                9–11 October 2026
            </div>
            <h1 class="pma-display text-white text-4xl mb-2">Camp Meeting<br><span style="color: var(--color-ochre-light, #D4A574);">2026</span></h1>
            <p class="pma-body text-white/60 text-sm">Wilderness Ebb &amp; Flow Rest Camp</p>
        </div>

        <div class="rounded-2xl p-8" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);">
            <form action="{{ route('camp-meeting.unlock') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-white/70 text-sm mb-2 pma-body">Password</label>
                    <input type="password" name="password" autofocus
                           class="w-full px-4 py-3 rounded-lg pma-body text-white placeholder-white/30 focus:outline-none focus:ring-2"
                           style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);"
                           placeholder="Enter password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full pma-btn pma-btn-primary py-3">
                    Enter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
