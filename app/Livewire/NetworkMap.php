<?php

namespace App\Livewire;

use App\Models\NetworkMember;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NetworkMap extends Component
{
    #[Url(as: 'search')]
    public $searchLocation = '';

    #[Url(as: 'type')]
    public $typeFilter = 'all';

    #[Url(as: 'language')]
    public $languageFilter = 'all';

    public function mount()
    {
        $this->searchLocation = request('search', '');
        $this->typeFilter = request('type', 'all');
        $this->languageFilter = request('language', 'all');
    }

    public function getNetworkMembersProperty()
    {
        $query = NetworkMember::query()
            ->approved()
            ->with('languages');

        // Filter by type
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        // Filter by language
        if ($this->languageFilter !== 'all') {
            $query->whereHas('languages', function ($q) {
                $q->where('code', $this->languageFilter);
            });
        }

        // Location search (basic implementation)
        if (!empty($this->searchLocation)) {
            // This is a simple search - in production you might want to use geocoding
            $query->where('name', 'like', '%' . $this->searchLocation . '%')
                  ->orWhere('address', 'like', '%' . $this->searchLocation . '%')
                  ->orWhere('bio', 'like', '%' . $this->searchLocation . '%');
        }

        return $query->get();
    }

    // Method to get filtered data for JavaScript
    public function getFilteredNetworkMembers()
    {
        return $this->networkMembers->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'type' => $member->type,
                'image_path' => $member->image_path,
                'professional_skills' => $member->professional_skills,
                'ministry_skills' => $member->ministry_skills,
                'latitude' => $member->latitude,
                'longitude' => $member->longitude,
                'bio' => $member->bio,
                'email' => $member->email,
                'phone' => $member->phone,
                'show_email' => $member->show_email,
                'show_phone' => $member->show_phone,
                'meeting_times' => $member->meeting_times,
                'languages' => $member->languages->map(function ($language) {
                    return [
                        'id' => $language->id,
                        'name' => $language->name,
                        'code' => $language->code
                    ];
                })
            ];
        })->toArray();
    }

    // Method to get filtered data for JavaScript
    public function getNetworkMembers()
    {
        return $this->getFilteredNetworkMembers();
    }

    // Force reactivity - this will trigger re-rendering when filters change
    public function getNetworkMembersJsonProperty()
    {
        return json_encode($this->getFilteredNetworkMembers());
    }

    public function updatedSearchLocation()
    {
        // Filters will automatically trigger re-render
    }

    public function updatedTypeFilter()
    {
        // Filters will automatically trigger re-render
    }

    public function updatedLanguageFilter()
    {
        // Filters will automatically trigger re-render
    }

    public function getAvailableLanguagesProperty()
    {
        return \App\Models\Language::orderBy('name')->get();
    }

    public function render()
    {
        $networkMembers = $this->networkMembers;
        $availableLanguages = $this->availableLanguages;

        return view('livewire.network-map', compact('networkMembers', 'availableLanguages'));
    }
}
