<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LocationPicker extends Component
{
    public $city;

    public $province;

    public $country;

    public $latitude;

    public $longitude;

    public $locationType = 'individual';

    public function mount(): void
    {
        $user = Auth::user();
        $this->city = $user->city;
        $this->province = $user->province;
        $this->country = $user->country;
        $this->latitude = $user->latitude;
        $this->longitude = $user->longitude;
        $this->locationType = $user->location_type ?? 'individual';
    }

    public function updateLocation($city, $province, $country, $latitude, $longitude): void
    {
        $this->city = $city;
        $this->province = $province;
        $this->country = $country;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function saveLocation(): void
    {
        $this->validate([
            'city' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'locationType' => 'required|in:individual,fellowship,ministry',
        ]);

        $user = Auth::user();
        $user->update([
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_type' => $this->locationType,
        ]);

        $this->dispatch('location-saved');
        session()->flash('message', 'Location saved successfully!');
    }

    public function render()
    {
        return view('livewire.location-picker');
    }
}
