<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Onboarding extends Component
{
    public int $step = 1;

    public function mount(): void
    {
        // Redirect to dashboard if user already has a profile
        if (auth()->user()->individualProfile ||
            auth()->user()->fellowshipProfiles()->exists() ||
            auth()->user()->ministries()->exists()) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}
