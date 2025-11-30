<?php

namespace App\Livewire;

use App\Models\Short;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShortsList extends Component
{
    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'tag')]
    public string $selectedTag = '';

    public function updatingSearch(): void
    {
        // Reset handled automatically
    }

    public function updatingSelectedTag(): void
    {
        // Reset handled automatically
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'selectedTag']);
    }

    public function selectTag(string $tag): void
    {
        $this->selectedTag = $this->selectedTag === $tag ? '' : $tag;
    }

    public function render()
    {
        $query = Short::query()->published()->ordered();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedTag) {
            $query->whereJsonContains('tags', $this->selectedTag);
        }

        $shorts = $query->get();

        $allTags = Short::published()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('livewire.shorts-list', [
            'shorts' => $shorts,
            'allTags' => $allTags,
        ]);
    }
}
