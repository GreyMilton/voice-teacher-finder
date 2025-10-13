<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])->assertOk();
});
