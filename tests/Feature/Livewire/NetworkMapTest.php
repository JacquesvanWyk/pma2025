<?php

use App\Livewire\NetworkMap;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(NetworkMap::class)
        ->assertStatus(200);
});
