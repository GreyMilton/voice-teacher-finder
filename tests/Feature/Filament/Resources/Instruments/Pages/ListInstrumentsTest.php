<?php

use App\Filament\Resources\Instruments\Pages\ListInstruments;
use App\Models\Instrument;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $instruments = Instrument::limit(random_int(1, 10))->get();

    livewire(ListInstruments::class)
        ->assertOk()
        ->assertCanSeeTableRecords($instruments);
});

test('admin can access page', function () {
    Instrument::limit(random_int(1, 10))->get();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListInstruments::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Instrument::limit(random_int(1, 10))->get();

    $this->assertGuest()
        ->get(ListInstruments::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('table contains required columns', function () {
    livewire(ListInstruments::class)
        ->assertTableColumnExists('english_name')
        ->assertTableColumnExists('teachers_count')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('updated_at');
});

test('table columns display the correct values for a given instrument', function () {
    $instrument = Instrument::inRandomOrder()->first();
    $instrument->teachers()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    livewire(ListInstruments::class)
        ->assertTableColumnStateSet('english_name', $instrument->english_name, record: $instrument)
        ->assertTableColumnStateNotSet('english_name', 'Something Else', record: $instrument)
        ->assertTableColumnStateSet('teachers_count', $instrument->teachers_count, record: $instrument)
        ->assertTableColumnStateNotSet('teachers_count', 99, record: $instrument)
        ->assertTableColumnStateSet('created_at', $instrument->created_at, record: $instrument)
        ->assertTableColumnStateNotSet('created_at', now()->subMillennia(), record: $instrument)
        ->assertTableColumnStateSet('updated_at', $instrument->updated_at, record: $instrument)
        ->assertTableColumnStateNotSet('updated_at', now()->subMillennia(), record: $instrument);
});

test('table columns have correct initial visibility', function () {
    livewire(ListInstruments::class)
        // initially visible columns
        ->assertCanRenderTableColumn('english_name')
        ->assertCanRenderTableColumn('teachers_count')
        // initially hidden columns
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

test("table 'english_name' columns are searchable", function () {
    $english_name = Instrument::inRandomOrder()->first()->english_name;

    livewire(ListInstruments::class)
        ->searchTable($english_name)
        ->assertCanSeeTableRecords(
            Instrument::whereLike('english_name', "%{$english_name}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Instrument::whereNotLike('english_name', "%{$english_name}%")->get(),
        );
});

test("table can be sorted by 'english_name'", function () {
    $instrumentsSortedAsc = Instrument::orderBy('english_name')->get();
    $instrumentsSortedDesc = Instrument::orderBy('english_name', 'desc')->get();

    livewire(ListInstruments::class)
        ->sortTable('english_name')
        ->assertCanSeeTableRecords($instrumentsSortedAsc, inOrder: true)
        ->sortTable('english_name', 'desc')
        ->assertCanSeeTableRecords($instrumentsSortedDesc, inOrder: true);
});

test("table can be sorted by 'teachers_count'", function () {
    $instrument = Instrument::inRandomOrder()->first();
    $instrument->teachers()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    $instrument2 = Instrument::whereNot('id', $instrument->id)
        ->inRandomOrder()
        ->first();
    $instrument2->teachers()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    $instrumentsSortedAsc = Instrument::withCount('teachers')
        ->orderBy('teachers_count')
        ->get();
    $instrumentsSortedDesc = Instrument::withCount('teachers')
        ->orderBy('teachers_count', 'desc')
        ->get();

    livewire(ListInstruments::class)
        ->sortTable('teachers_count')
        ->assertCanSeeTableRecords($instrumentsSortedAsc, inOrder: true)
        ->sortTable('teachers_count', 'desc')
        ->assertCanSeeTableRecords($instrumentsSortedDesc, inOrder: true);
});

test("table can be sorted by 'created_at'", function () {
    $instrumentsSortedAsc = Instrument::orderBy('created_at')->get();
    $instrumentsSortedDesc = Instrument::orderBy('created_at', 'desc')->get();

    livewire(ListInstruments::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($instrumentsSortedAsc, inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($instrumentsSortedDesc, inOrder: true);
});

test("table can be sorted by 'updated_at'", function () {
    $instrumentsSortedAsc = Instrument::orderBy('updated_at')->get();
    $instrumentsSortedDesc = Instrument::orderBy('updated_at', 'desc')->get();

    livewire(ListInstruments::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($instrumentsSortedAsc, inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($instrumentsSortedDesc, inOrder: true);
});

test("table default sorting is 'english_name' in ascending order", function () {
    $instrumentsSortedAsc = Instrument::orderBy('english_name')->get();

    livewire(ListInstruments::class)
        ->assertCanSeeTableRecords($instrumentsSortedAsc, inOrder: true);
});
