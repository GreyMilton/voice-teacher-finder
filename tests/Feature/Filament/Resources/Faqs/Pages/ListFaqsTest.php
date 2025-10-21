<?php

use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $faqs = Faq::factory(random_int(1, 10))->create();

    livewire(ListFaqs::class)
        ->assertOk()
        ->assertCanSeeTableRecords($faqs);
});

test('admin can access page', function () {
    Faq::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListFaqs::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Faq::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListFaqs::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('table contains required columns', function () {
    Faq::factory(random_int(1, 10))->create();

    livewire(ListFaqs::class)
        ->assertTableColumnExists('order')
        ->assertTableColumnExists('question')
        ->assertTableColumnExists('answer')
        ->assertTableColumnExists('is_visible_on_faqs_page')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('updated_at');
});

test('table columns display the correct values for a given faq', function () {
    Faq::factory(random_int(1, 10))->create();
    $faq = Faq::inRandomOrder()->first();

    livewire(ListFaqs::class)
        ->assertTableColumnStateSet('order', $faq->order, record: $faq)
        ->assertTableColumnStateNotSet('order', 99, record: $faq)
        ->assertTableColumnStateSet('question', $faq->question, record: $faq)
        ->assertTableColumnStateNotSet('question', 'Something Else', record: $faq)
        ->assertTableColumnStateSet('answer', $faq->answer, record: $faq)
        ->assertTableColumnStateNotSet('answer', 'Wrong answer', record: $faq)
        ->assertTableColumnStateSet('is_visible_on_faqs_page', $faq->is_visible_on_faqs_page, record: $faq)
        ->assertTableColumnStateNotSet('is_visible_on_faqs_page', ! $faq->is_visible_on_faqs_page, record: $faq)
        ->assertTableColumnStateSet('created_at', $faq->created_at, record: $faq)
        ->assertTableColumnStateNotSet('created_at', now()->subMillennia(), record: $faq)
        ->assertTableColumnStateSet('updated_at', $faq->updated_at, record: $faq)
        ->assertTableColumnStateNotSet('updated_at', now()->subMillennia(), record: $faq);
});

test('table columns have correct initial visibility', function () {
    Faq::factory(random_int(1, 10))->create();

    livewire(ListFaqs::class)
        // initially visible columns
        ->assertCanRenderTableColumn('order')
        ->assertCanRenderTableColumn('question')
        ->assertCanRenderTableColumn('answer')
        ->assertCanRenderTableColumn('is_visible_on_faqs_page')
        // initially hidden columns
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

test("table 'question' columns are searchable", function () {
    $faqs = Faq::factory(random_int(5, 10))->create();
    $question = $faqs->random()->question;

    livewire(ListFaqs::class)
        ->searchTable($question)
        ->assertCanSeeTableRecords(Faq::whereLike('question', "%{$question}%")->get())
        ->assertCanNotSeeTableRecords(Faq::whereNotLike('question', "%{$question}%")->get());
});

test("table 'answer' columns are searchable", function () {
    $faqs = Faq::factory(random_int(5, 10))->create();
    $answer = $faqs->random()->answer;

    livewire(ListFaqs::class)
        ->searchTable($answer)
        ->assertCanSeeTableRecords(Faq::whereLike('answer', "%{$answer}%")->get())
        ->assertCanNotSeeTableRecords(Faq::whereNotLike('answer', "%{$answer}%")->get());
});

test("table can be sorted by 'order'", function () {
    Faq::factory(random_int(5, 10))->create();

    $faqsSortedAsc = Faq::orderBy('order')->get();
    $faqsSortedDesc = Faq::orderBy('order', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('order')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('order', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table can be sorted by 'question'", function () {
    Faq::factory(random_int(5, 10))->create();

    $faqsSortedAsc = Faq::orderBy('question')->get();
    $faqsSortedDesc = Faq::orderBy('question', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('question')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('question', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table can be sorted by 'answer'", function () {
    Faq::factory(random_int(5, 10))->create();

    $faqsSortedAsc = Faq::orderBy('answer')->get();
    $faqsSortedDesc = Faq::orderBy('answer', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('answer')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('answer', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table can be sorted by 'is_visible_on_faqs_page'", function () {
    Faq::factory(random_int(5, 10))->create();

    $faqsSortedAsc = Faq::orderBy('is_visible_on_faqs_page')->get();
    $faqsSortedDesc = Faq::orderBy('is_visible_on_faqs_page', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('is_visible_on_faqs_page')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('is_visible_on_faqs_page', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table can be sorted by 'created_at'", function () {
    Faq::factory()->create(['created_at' => now()->subMinutes(2)]);
    Faq::factory()->create(['created_at' => now()->subMinute()]);
    Faq::factory()->create();

    $faqsSortedAsc = Faq::orderBy('created_at')->get();
    $faqsSortedDesc = Faq::orderBy('created_at', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table can be sorted by 'updated_at'", function () {
    Faq::factory()->create(['updated_at' => now()->subMinutes(2)]);
    Faq::factory()->create(['updated_at' => now()->subMinute()]);
    Faq::factory()->create();

    $faqsSortedAsc = Faq::orderBy('updated_at')->get();
    $faqsSortedDesc = Faq::orderBy('updated_at', 'desc')->get();

    livewire(ListFaqs::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($faqsSortedDesc, inOrder: true);
});

test("table default sorting is 'order' in ascending order", function () {
    Faq::factory(random_int(5, 10))->create();

    $faqsSortedAsc = Faq::orderBy('order')->get();

    livewire(ListFaqs::class)
        ->assertCanSeeTableRecords($faqsSortedAsc, inOrder: true);
});
