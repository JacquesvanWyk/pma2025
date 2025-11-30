@extends('layouts.public')

@section('title', 'Network Member Approval')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        @if($success)
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Approved!</h1>
        @else
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Already Processed</h1>
        @endif

        <p class="text-gray-600 mb-6">{{ $message }}</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <p class="text-sm text-gray-500 mb-1">Member</p>
            <p class="font-semibold text-gray-900">{{ $networkMember->name }}</p>
            <p class="text-sm text-gray-600">{{ $networkMember->email }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ ucfirst($networkMember->type) }} • {{ $networkMember->city }}, {{ $networkMember->province }}</p>
        </div>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('network.index') }}" class="px-4 py-2 bg-[var(--color-pma-green)] text-white rounded-lg hover:opacity-90 transition-all">
                View Network Map
            </a>
            <a href="{{ url('/admin/network-members') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                Admin Panel
            </a>
        </div>
    </div>
</div>
@endsection
