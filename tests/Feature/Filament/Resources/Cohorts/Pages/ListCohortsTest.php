<?php

use App\Enums\CohortType;
use App\Filament\Resources\Cohorts\Pages\ListCohorts;
use App\Models\Cohort;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $cohorts = Cohort::factory(random_int(1, 10))->create();

    livewire(ListCohorts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($cohorts);
});

test('admin can access page', function () {
    Cohort::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListCohorts::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Cohort::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListCohorts::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('table contains required columns', function () {
    Cohort::factory(random_int(1, 10))->create();

    livewire(ListCohorts::class)
        ->assertTableColumnExists('name')
        ->assertTableColumnExists('completion_date')
        ->assertTableColumnExists('cohort_type')
        ->assertTableColumnExists('teachers_count')
        ->assertTableColumnExists('authorisationExpirationDate')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('updated_at');
});

test('table columns display the correct values for a given cohort', function () {
    $cohort = Cohort::factory()
        ->initialAuthorisation()
        ->hasAttached(Teacher::factory(2)->create())
        ->create();

    livewire(ListCohorts::class)
        ->assertTableColumnStateSet('name', $cohort->name, record: $cohort)
        ->assertTableColumnStateNotSet('name', 'Something Else', record: $cohort)
        ->assertTableColumnStateSet('completion_date', $cohort->completion_date, record: $cohort)
        ->assertTableColumnStateNotSet('completion_date', now()->subMillennia(), record: $cohort)
        ->assertTableColumnFormattedStateSet('cohort_type', $cohort->cohort_type->getLabel(), record: $cohort)
        ->assertTableColumnFormattedStateNotSet('cohort_type', CohortType::Update->getLabel(), record: $cohort)
        // missing teachers_count as the asserts strangely evaluate the column value to null
        ->assertTableColumnStateSet('authorisationExpirationDate', $cohort->authorisationExpirationDate, record: $cohort)
        ->assertTableColumnStateNotSet('authorisationExpirationDate', now()->subMillennia(), record: $cohort)
        ->assertTableColumnStateSet('created_at', $cohort->created_at, record: $cohort)
        ->assertTableColumnStateNotSet('created_at', now()->subMillennia(), record: $cohort)
        ->assertTableColumnStateSet('updated_at', $cohort->updated_at, record: $cohort)
        ->assertTableColumnStateNotSet('updated_at', now()->subMillennia(), record: $cohort);
});

test('table columns have correct initial visibility', function () {
    Cohort::factory(random_int(1, 10))->create();

    livewire(ListCohorts::class)
        // initially visible columns
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('completion_date')
        ->assertCanRenderTableColumn('cohort_type')
        ->assertCanRenderTableColumn('teachers_count')
        ->assertCanRenderTableColumn('authorisationExpirationDate')
        // initially hidden columns
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

test("table 'name' columns are searchable", function () {
    $cohorts = Cohort::factory(random_int(5, 10))->create();
    $name = $cohorts->random()->name;

    livewire(ListCohorts::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(Cohort::whereLike('name', "%{$name}%")->get())
        ->assertCanNotSeeTableRecords(Cohort::whereNotLike('name', "%{$name}%")->get());
});

test("table 'completion_date' columns are searchable", function () {
    $cohorts = Cohort::factory(random_int(5, 10))->create();
    $completion_date = $cohorts->random()->completion_date;

    livewire(ListCohorts::class)
        ->searchTable($completion_date)
        ->assertCanSeeTableRecords($cohorts->where('completion_date', $completion_date))
        ->assertCanNotSeeTableRecords($cohorts->where('completion_date', '!=', $completion_date));
});

test("table 'cohort_type' columns are searchable", function () {
    $cohorts = Cohort::factory(random_int(5, 10))->create();
    $cohort_type = $cohorts->random()->cohort_type->value;

    livewire(ListCohorts::class)
        ->searchTable($cohort_type)
        ->assertCanSeeTableRecords($cohorts->where('cohort_type', $cohort_type))
        ->assertCanNotSeeTableRecords($cohorts->where('cohort_type', '!=', $cohort_type));
});

test("table can be sorted by 'name'", function () {
    Cohort::factory(random_int(5, 10))->create();
    $cohortsSortedAsc = Cohort::orderBy('name')->get();
    $cohortsSortedDesc = Cohort::orderBy('name', 'desc')->get();

    livewire(ListCohorts::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table can be sorted by 'completion_date'", function () {
    Cohort::factory(random_int(5, 10))->create();
    $cohortsSortedAsc = Cohort::orderBy('completion_date')->get();
    $cohortsSortedDesc = Cohort::orderBy('completion_date', 'desc')->get();

    livewire(ListCohorts::class)
        ->sortTable('completion_date')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('completion_date', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table can be sorted by 'cohort_type'", function () {
    Cohort::factory(random_int(3, 5))->initialAuthorisation()->create();
    Cohort::factory(random_int(3, 5))->updateCohort()->create();

    $cohortsSortedAsc = Cohort::orderBy('cohort_type')
        ->orderBy('completion_date')
        ->get();
    $cohortsSortedDesc = Cohort::orderBy('cohort_type', 'desc')
        ->orderBy('completion_date')
        ->get();

    livewire(ListCohorts::class)
        ->sortTable('cohort_type')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('cohort_type', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table can be sorted by 'teachers_count'", function () {
    Cohort::factory()->has(Teacher::factory())->create();
    Cohort::factory()->hasAttached(Teacher::factory(2)->create())->create();
    Cohort::factory()->hasAttached(Teacher::factory(3)->create())->create();

    $cohortsSortedAsc = Cohort::withCount('teachers')
        ->orderBy('teachers_count')
        ->get();
    $cohortsSortedDesc = Cohort::withCount('teachers')
        ->orderBy('teachers_count', 'desc')
        ->get();

    livewire(ListCohorts::class)
        ->sortTable('teachers_count')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('teachers_count', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table can be sorted by 'created_at'", function () {
    Cohort::factory()->create(['created_at' => now()->subMinutes(2)]);
    Cohort::factory()->create(['created_at' => now()->subMinute()]);
    Cohort::factory()->create();

    $cohortsSortedAsc = Cohort::orderBy('created_at')->get();
    $cohortsSortedDesc = Cohort::orderBy('created_at', 'desc')->get();

    livewire(ListCohorts::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table can be sorted by 'updated_at'", function () {
    Cohort::factory()->create(['updated_at' => now()->subMinutes(2)]);
    Cohort::factory()->create(['updated_at' => now()->subMinute()]);
    Cohort::factory()->create();

    $cohortsSortedAsc = Cohort::orderBy('updated_at')->get();
    $cohortsSortedDesc = Cohort::orderBy('updated_at', 'desc')->get();

    livewire(ListCohorts::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($cohortsSortedDesc, inOrder: true);
});

test("table default sorting is 'completion_date' in ascending order", function () {
    Cohort::factory(random_int(5, 10))->create();

    $cohortsSortedAsc = Cohort::orderBy('completion_date')->get();

    livewire(ListCohorts::class)
        ->assertCanSeeTableRecords($cohortsSortedAsc, inOrder: true);
});
