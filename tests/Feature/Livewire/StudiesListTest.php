<?php

use App\Livewire\StudiesList;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(StudiesList::class)
        ->assertStatus(200);
});
