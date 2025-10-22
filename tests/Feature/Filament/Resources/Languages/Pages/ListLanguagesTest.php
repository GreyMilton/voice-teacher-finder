<?php

use App\Filament\Resources\Languages\Pages\ListLanguages;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $languages = Language::limit(random_int(1, 10))->get();

    livewire(ListLanguages::class)
        ->assertOk()
        ->assertCanSeeTableRecords($languages);
});

test('admin can access page', function () {
    Language::limit(random_int(1, 10))->get();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListLanguages::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Language::limit(random_int(1, 10))->get();

    $this->assertGuest()
        ->get(ListLanguages::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('table contains required columns', function () {
    livewire(ListLanguages::class)
        ->assertTableColumnExists('english_name')
        ->assertTableColumnExists('teachers_who_sing_count')
        ->assertTableColumnExists('teachers_who_teach_in_count')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('updated_at');
});

test('table columns display the correct values for a given language', function () {
    $language = Language::inRandomOrder()->first();
    $language->teachersWhoSing()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );
    $language->teachersWhoTeachIn()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    livewire(ListLanguages::class)
        ->assertTableColumnStateSet('english_name', $language->english_name, record: $language)
        ->assertTableColumnStateNotSet('english_name', 'Something Else', record: $language)
        ->assertTableColumnStateSet('teachers_who_sing_count', $language->teachers_who_sing_count, record: $language)
        ->assertTableColumnStateNotSet('teachers_who_sing_count', 99, record: $language)
        ->assertTableColumnStateSet('teachers_who_teach_in_count', $language->teachers_who_teach_in_count, record: $language)
        ->assertTableColumnStateNotSet('teachers_who_teach_in_count', 99, record: $language)
        ->assertTableColumnStateSet('created_at', $language->created_at, record: $language)
        ->assertTableColumnStateNotSet('created_at', now()->subMillennia(), record: $language)
        ->assertTableColumnStateSet('updated_at', $language->updated_at, record: $language)
        ->assertTableColumnStateNotSet('updated_at', now()->subMillennia(), record: $language);
});

test('table columns have correct initial visibility', function () {
    livewire(ListLanguages::class)
        // initially visible columns
        ->assertCanRenderTableColumn('english_name')
        ->assertCanRenderTableColumn('teachers_who_sing_count')
        ->assertCanRenderTableColumn('teachers_who_teach_in_count')
        // initially hidden columns
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

test("table 'english_name' columns are searchable", function () {
    $english_name = Language::inRandomOrder()->first()->english_name;

    livewire(ListLanguages::class)
        ->searchTable($english_name)
        ->assertCanSeeTableRecords(
            Language::whereLike('english_name', "%{$english_name}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Language::whereNotLike('english_name', "%{$english_name}%")->get(),
        );
});

test("table can be sorted by 'english_name'", function () {
    $languagesSortedAsc = Language::orderBy('english_name')
        ->limit(10)
        ->get();
    $languagesSortedDesc = Language::orderBy('english_name', 'desc')
        ->limit(10)
        ->get();

    livewire(ListLanguages::class)
        ->sortTable('english_name')
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true)
        ->sortTable('english_name', 'desc')
        ->assertCanSeeTableRecords($languagesSortedDesc, inOrder: true);
});

test("table can be sorted by 'teachers_who_sing_count'", function () {
    $language = Language::inRandomOrder()->first();
    $language->teachersWhoSing()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    $language2 = Language::whereNot('id', $language->id)
        ->inRandomOrder()
        ->first();
    $language2->teachersWhoSing()->attach(
        Teacher::factory(random_int(1, 5))->create(),
    );

    $languagesSortedAsc = Language::withCount('teachersWhoSing')
        ->orderBy('teachers_who_sing_count')
        ->limit(10)
        ->get();
    $languagesSortedDesc = Language::withCount('teachersWhoSing')
        ->orderBy('teachers_who_sing_count', 'desc')
        ->limit(10)
        ->get();

    livewire(ListLanguages::class)
        ->sortTable('teachers_who_sing_count')
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true)
        ->sortTable('teachers_who_sing_count', 'desc')
        ->assertCanSeeTableRecords($languagesSortedDesc, inOrder: true);
});

test("table can be sorted by 'teachers_who_teach_in_count'", function () {
    $language = Language::inRandomOrder()->first();
    $language->teachersWhoTeachIn()->attach(
        Teacher::factory()->create(),
    );

    $language2 = Language::whereNot('id', $language->id)
        ->inRandomOrder()
        ->first();
    $language2->teachersWhoTeachIn()->attach(
        Teacher::factory(random_int(2, 5))->create(),
    );
    $languagesSortedAsc = Language::withCount('teachersWhoTeachIn')
        ->orderBy('teachers_who_teach_in_count')
        ->limit(10)
        ->get();
    $languagesSortedDesc = Language::withCount('teachersWhoTeachIn')
        ->orderBy('teachers_who_teach_in_count', 'desc')
        ->limit(10)
        ->get();

    livewire(ListLanguages::class)
        ->sortTable('teachers_who_teach_in_count')
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true)
        ->sortTable('teachers_who_teach_in_count', 'desc')
        ->assertCanSeeTableRecords($languagesSortedDesc, inOrder: true);
});

test("table can be sorted by 'created_at'", function () {
    $languagesSortedAsc = Language::orderBy('created_at')
        ->limit(10)
        ->get();
    $languagesSortedDesc = Language::orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    livewire(ListLanguages::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($languagesSortedDesc, inOrder: true);
});

test("table can be sorted by 'updated_at'", function () {
    $languagesSortedAsc = Language::orderBy('updated_at')
        ->limit(10)
        ->get();
    $languagesSortedDesc = Language::orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();

    livewire(ListLanguages::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($languagesSortedDesc, inOrder: true);
});

test("table default sorting is 'english_name' in ascending order", function () {
    $languagesSortedAsc = Language::orderBy('english_name')->limit(10)->get();

    livewire(ListLanguages::class)
        ->assertCanSeeTableRecords($languagesSortedAsc, inOrder: true);
});
