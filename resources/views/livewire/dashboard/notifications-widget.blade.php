<?php

use Livewire\Volt\Component;

new class extends Component {
    public function markAsRead(string $notificationId): void
    {
        auth()->user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function getNotifications()
    {
        return auth()->user()->notifications()->latest()->limit(5)->get();
    }
}; ?>

<div class="bg-white rounded-lg shadow border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-lg" style="color: var(--color-indigo);">
            Recent Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="ml-2 px-2 py-1 text-xs rounded-full" style="background: var(--color-pma-green); color: white;">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </h3>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <button
                wire:click="markAllAsRead"
                class="text-sm font-medium hover:underline"
                style="color: var(--color-pma-green);"
            >
                Mark all as read
            </button>
        @endif
    </div>

    <div class="space-y-3">
        @php
            $notifications = $this->getNotifications();
        @endphp

        @forelse($notifications as $notification)
            <div
                wire:key="notification-{{ $notification->id }}"
                class="flex gap-3 p-3 rounded-lg transition {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }}"
            >
                <div class="flex-shrink-0">
                    @if(isset($notification->data['reaction_emoji']))
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl" style="background: var(--color-cream);">
                            {{ $notification->data['reaction_emoji'] }}
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                            <svg class="h-5 w-5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium" style="color: var(--color-indigo);">
                        {{ $notification->data['message'] }}
                    </p>
                    @if(isset($notification->data['post_title']))
                        <p class="text-xs mt-1 truncate" style="color: var(--color-olive);">
                            "{{ $notification->data['post_title'] }}"
                        </p>
                    @endif
                    <p class="text-xs mt-1" style="color: var(--color-olive);">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>

                @if(!$notification->read_at)
                    <button
                        wire:click="markAsRead('{{ $notification->id }}')"
                        class="flex-shrink-0 text-xs font-medium hover:underline"
                        style="color: var(--color-pma-green);"
                    >
                        Mark read
                    </button>
                @endif
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="text-sm" style="color: var(--color-olive);">No notifications yet</p>
            </div>
        @endforelse
    </div>

    @if($notifications->count() > 0)
        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
            <a href="/notifications" class="text-sm font-medium hover:underline" style="color: var(--color-pma-green);">
                View all notifications
            </a>
        </div>
    @endif
</div>
