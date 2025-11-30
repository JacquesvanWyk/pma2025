<?php

namespace App\Livewire;

use App\Models\Ministry;
use App\Models\NetworkMember;
use Livewire\Attributes\Url;
use Livewire\Component;

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
        if ($this->typeFilter === 'ministry') {
            return collect();
        }

        $query = NetworkMember::query()
            ->approved()
            ->with('languages');

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->languageFilter !== 'all') {
            $query->whereHas('languages', function ($q) {
                $q->where('code', $this->languageFilter);
            });
        }

        if (! empty($this->searchLocation)) {
            $query->where('name', 'like', '%'.$this->searchLocation.'%')
                ->orWhere('address', 'like', '%'.$this->searchLocation.'%')
                ->orWhere('bio', 'like', '%'.$this->searchLocation.'%');
        }

        return $query->get();
    }

    public function getMinistriesProperty()
    {
        if ($this->typeFilter !== 'all' && $this->typeFilter !== 'ministry') {
            return collect();
        }

        $query = Ministry::query()->approved();

        if (! empty($this->searchLocation)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->searchLocation.'%')
                    ->orWhere('description', 'like', '%'.$this->searchLocation.'%')
                    ->orWhere('city', 'like', '%'.$this->searchLocation.'%');
            });
        }

        return $query->get();
    }

    public function getFilteredNetworkMembers()
    {
        return $this->networkMembers->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'type' => $member->type,
                'total_believers' => $member->total_believers,
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
                'website_url' => $member->website_url,
                'facebook_url' => $member->facebook_url,
                'twitter_url' => $member->twitter_url,
                'youtube_url' => $member->youtube_url,
                'languages' => $member->languages->map(function ($language) {
                    return [
                        'id' => $language->id,
                        'name' => $language->name,
                        'code' => $language->code,
                    ];
                }),
            ];
        })->toArray();
    }

    public function getFilteredMinistries()
    {
        return $this->ministries->map(function ($ministry) {
            return [
                'id' => $ministry->id,
                'name' => $ministry->name,
                'type' => 'ministry',
                'logo' => $ministry->logo,
                'description' => $ministry->description,
                'focus_areas' => $ministry->focus_areas,
                'languages' => $ministry->languages,
                'tags' => $ministry->tags,
                'latitude' => $ministry->latitude,
                'longitude' => $ministry->longitude,
                'city' => $ministry->city,
                'province' => $ministry->province,
                'country' => $ministry->country,
                'email' => $ministry->email,
                'phone' => $ministry->phone,
                'show_email' => $ministry->show_email,
                'show_phone' => $ministry->show_phone,
                'website' => $ministry->website,
                'facebook' => $ministry->facebook,
                'twitter' => $ministry->twitter,
                'instagram' => $ministry->instagram,
                'youtube' => $ministry->youtube,
            ];
        })->toArray();
    }

    public function getNetworkMembers()
    {
        return $this->getFilteredNetworkMembers();
    }

    public function getAllMarkersJsonProperty()
    {
        $members = $this->getFilteredNetworkMembers();
        $ministries = $this->getFilteredMinistries();

        return json_encode(array_merge($members, $ministries));
    }

    public function getNetworkMembersJsonProperty()
    {
        return json_encode($this->getFilteredNetworkMembers());
    }

    public function getMinistriesJsonProperty()
    {
        return json_encode($this->getFilteredMinistries());
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

    public function getTopProfessionalSkillsProperty()
    {
        return $this->networkMembers
            ->pluck('professional_skills')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(3)
            ->keys();
    }

    public function getTopMinistrySkillsProperty()
    {
        return $this->networkMembers
            ->pluck('ministry_skills')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(3)
            ->keys();
    }

    public function getTopLanguagesProperty()
    {
        return $this->networkMembers
            ->pluck('languages')
            ->flatten()
            ->countBy('name')
            ->sortDesc()
            ->take(3)
            ->keys();
    }

    public function getGeographicSpreadProperty()
    {
        return $this->networkMembers
            ->filter(fn ($m) => $m->country && $m->province && $m->type === 'individual')
            ->groupBy('country')
            ->map(fn ($members) => $members->groupBy('province')
                ->map(fn ($provinceMembers) => $provinceMembers->sum('total_believers'))
                ->sortKeys()
            )
            ->sortKeys();
    }

    public function getNewThisMonthProperty()
    {
        return $this->networkMembers
            ->where('approved_at', '>=', now()->subDays(30))
            ->count();
    }

    public function render()
    {
        return view('livewire.network-map', [
            'networkMembers' => $this->networkMembers,
            'availableLanguages' => $this->availableLanguages,
            'topProfessionalSkills' => $this->topProfessionalSkills,
            'topMinistrySkills' => $this->topMinistrySkills,
            'topLanguages' => $this->topLanguages,
            'geographicSpread' => $this->geographicSpread,
            'newThisMonth' => $this->newThisMonth,
        ]);
    }
}
