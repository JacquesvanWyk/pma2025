<?php

use App\Models\FeedPost;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $type = 'update';
    public string $title = '';
    public string $content = '';
    public $images = [];
    public bool $showModal = false;

    public function rules(): array
    {
        return [
            'type' => 'required|in:update,prayer,testimony,resource,event',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:5000',
            'images.*' => 'nullable|image|max:10240',
        ];
    }

    public function createPost(): void
    {
        if (! auth()->check()) {
            $this->dispatch('show-login-prompt');
            return;
        }

        $this->validate();

        $imagePaths = [];
        if (! empty($this->images)) {
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('feed/images', 'public');
            }
        }

        FeedPost::create([
            'author_type' => 'App\Models\User',
            'author_id' => auth()->id(),
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->content,
            'images' => ! empty($imagePaths) ? $imagePaths : null,
            'is_approved' => false,
        ]);

        session()->flash('message', 'Your post has been submitted for approval. It will appear on the network feed once reviewed.');

        $this->reset(['type', 'title', 'content', 'images', 'showModal']);
        $this->dispatch('post-created');
    }

    public function openModal(): void
    {
        if (! auth()->check()) {
            $this->dispatch('show-login-prompt');
            return;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['type', 'title', 'content', 'images']);
        $this->resetValidation();
    }
}; ?>

<div>
    <!-- Trigger Button -->
    <button
        wire:click="openModal"
        class="w-full px-6 py-3 rounded-lg font-medium transition"
        style="background: var(--color-pma-green); color: white;"
    >
        <svg class="inline-block h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create Post
    </button>

    <!-- Modal -->
    @if($showModal)
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
        x-data
        @click.self="$wire.closeModal()"
    >
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold" style="color: var(--color-indigo);">Create Post</h3>
                <button
                    wire:click="closeModal"
                    class="text-gray-400 hover:text-gray-600 transition"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit="createPost" class="p-6 space-y-6">
                <!-- Post Type -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Post Type *
                    </label>
                    <select
                        wire:model="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="update">Update</option>
                        <option value="prayer">Prayer Request</option>
                        <option value="testimony">Testimony</option>
                        <option value="resource">Resource</option>
                        <option value="event">Event</option>
                    </select>
                    @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Title (Optional) -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Title (Optional)
                    </label>
                    <input
                        type="text"
                        wire:model="title"
                        placeholder="Give your post a title..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                    @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Content *
                    </label>
                    <textarea
                        wire:model="content"
                        rows="6"
                        placeholder="Share your update, prayer request, testimony, or resource..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    ></textarea>
                    @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">Minimum 10 characters, maximum 5000</p>
                </div>

                <!-- Images -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Images (Optional)
                    </label>
                    <input
                        type="file"
                        wire:model="images"
                        multiple
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                    @error('images.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <p class="text-sm text-gray-500 mt-1">You can upload multiple images (max 10MB each)</p>

                    @if(!empty($images))
                    <div class="mt-3 flex gap-2 flex-wrap">
                        @foreach($images as $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg" />
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div wire:loading wire:target="images" class="text-sm text-gray-500 mt-2">
                        Uploading images...
                    </div>
                </div>

                <!-- Info Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm" style="color: var(--color-indigo);">
                        <strong>Note:</strong> Your post will be reviewed by our team before appearing on the network feed. This helps us maintain a safe and encouraging community.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-6 py-2 border-2 rounded-lg font-medium transition hover:bg-gray-50"
                        style="border-color: var(--color-indigo); color: var(--color-indigo);"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 rounded-lg font-medium transition"
                        style="background: var(--color-pma-green); color: white;"
                        wire:loading.attr="disabled"
                        wire:target="createPost"
                    >
                        <span wire:loading.remove wire:target="createPost">Submit Post</span>
                        <span wire:loading wire:target="createPost">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
