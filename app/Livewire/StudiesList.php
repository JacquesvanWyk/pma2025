<?php

namespace App\Livewire;

use App\Models\Study;
use App\Models\Tag;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudiesList extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'language')]
    public $language = 'all';

    #[Url(as: 'tags')]
    public array $selectedTags = [];

    #[Url(as: 'sort')]
    public $sort = 'newest';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLanguage()
    {
        $this->resetPage();
    }

    public function updatingSelectedTags()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'language', 'selectedTags', 'sort']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Study::query()
            ->published()
            ->with('tags');

        // Text search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%")
                    ->orWhere('excerpt', 'like', "%{$this->search}%");
            });
        }

        // Language filter
        if ($this->language && $this->language !== 'all') {
            $query->where('language', $this->language);
        }

        // Topic tags filter - ensure it's an array
        $tagsToFilter = is_array($this->selectedTags) ? $this->selectedTags : [];
        if (! empty($tagsToFilter)) {
            $query->whereHas('tags', function ($q) use ($tagsToFilter) {
                $q->whereIn('slug', $tagsToFilter);
            });
        }

        // Sorting
        match ($this->sort) {
            'oldest' => $query->oldest('published_at'),
            'title-asc' => $query->orderBy('title', 'asc'),
            'title-desc' => $query->orderBy('title', 'desc'),
            default => $query->latest('published_at'),
        };

        $studies = $query->paginate(12);

        $tags = Tag::whereHas('studies', function ($q) {
            $q->published();
        })->orderBy('name')->get();

        return view('livewire.studies-list', [
            'studies' => $studies,
            'tags' => $tags,
        ]);
    }
}
