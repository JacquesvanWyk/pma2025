<?php

namespace App\Livewire;

use App\Models\Study;
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
    public $selectedTags = [];

    #[Url(as: 'sort')]
    public $sort = 'newest';

    public $topics = [
        'pioneers' => 'Pioneers',
        'bible-scriptures' => 'Bible Scriptures',
        'ellen-white' => 'Ellen White',
        'evangelism' => 'Evangelism',
        'the-issues' => 'The Issues',
    ];

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
        $this->search = '';
        $this->language = 'all';
        $this->selectedTags = [];
        $this->sort = 'newest';
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

        // Topic tags filter
        if (! empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('slug', $this->selectedTags);
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

        return view('livewire.studies-list', [
            'studies' => $studies,
        ]);
    }
}
