<?php

use App\Models\Faq;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the faqs page', function () {
    $this->get(route('faqs'))
        ->assertStatus(200);
});

test('authenticated users can visit the faqs page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('faqs'))
        ->assertStatus(200);
});

test('all faqs set as visible are passed to the faqs page', function () {
    Faq::factory(4)
        ->create(['is_visible_on_faqs_page' => true]);

    $this->get(route('faqs'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('FaqsPage')
            ->has('faqs', 4)
        );
});

test('no faqs set as not visible are passed to the faqs page', function () {
    Faq::factory(4)
        ->create(['is_visible_on_faqs_page' => false]);

    $this->get(route('faqs'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('FaqsPage')
            ->has('faqs', 0)
        );
});

test('visible faqs on the faqs page are initially ordered correctly', function () {
    Faq::factory(3)->create(['is_visible_on_faqs_page' => true]);
    Faq::factory(3)->create(['is_visible_on_faqs_page' => false]);
    Faq::factory(3)->create(['is_visible_on_faqs_page' => true]);
    Faq::factory(3)->create(['is_visible_on_faqs_page' => false]);

    $faqs = Faq::whereIsVisibleOnFaqsPage(true)->get();

    // Validate setup
    expect($faqs->count())->toBe(6);
    $faqs->each(function (Faq $faq, int $key) {
        if ($key < 3) {
            expect($faq->id)->toBe($key + 1);
            expect($faq->order)->toBe($key + 1);
        } else {
            expect($faq->id)->toBe($key + 4);
            expect($faq->order)->toBe($key + 4);
        }
    });

    $this->get(route('faqs'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('FaqsPage')
            ->has('faqs', 6)
            ->where('faqs.0.id', $faqs->first()->id)
            ->where('faqs.1.id', $faqs->skip(1)->first()->id)
            ->where('faqs.2.id', $faqs->skip(2)->first()->id)
            ->where('faqs.3.id', $faqs->skip(3)->first()->id)
            ->where('faqs.4.id', $faqs->skip(4)->first()->id)
            ->where('faqs.5.id', $faqs->skip(5)->first()->id)
            ->where('faqs.0.question', $faqs->first()->question)
            ->where('faqs.1.question', $faqs->skip(1)->first()->question)
            ->where('faqs.2.question', $faqs->skip(2)->first()->question)
            ->where('faqs.3.question', $faqs->skip(3)->first()->question)
            ->where('faqs.4.question', $faqs->skip(4)->first()->question)
            ->where('faqs.5.question', $faqs->skip(5)->first()->question)
        );
});

test('reordered faqs are ordered correctly on the faqs page', function () {
    Faq::factory(6)->create(['is_visible_on_faqs_page' => true]);

    // Reorder the seeded faqs
    Faq::inRandomOrder()
        ->get()
        ->each(function (Faq $faq, int $key) {
            $faq->order = $key + 1;
            $faq->save();
        });

    $faqs = Faq::orderBy('order', 'asc')->get();

    // Validate setup
    expect($faqs->count())->toBe(6);
    $faqs->each(function (Faq $faq, int $key) {
        expect($faq->order)->toBe($key + 1);
    });

    $this->get(route('faqs'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('FaqsPage')
            ->has('faqs', 6)
            ->where('faqs.0.id', $faqs->first()->id)
            ->where('faqs.1.id', $faqs->skip(1)->first()->id)
            ->where('faqs.2.id', $faqs->skip(2)->first()->id)
            ->where('faqs.3.id', $faqs->skip(3)->first()->id)
            ->where('faqs.4.id', $faqs->skip(4)->first()->id)
            ->where('faqs.5.id', $faqs->skip(5)->first()->id)
            ->where('faqs.0.question', $faqs->first()->question)
            ->where('faqs.1.question', $faqs->skip(1)->first()->question)
            ->where('faqs.2.question', $faqs->skip(2)->first()->question)
            ->where('faqs.3.question', $faqs->skip(3)->first()->question)
            ->where('faqs.4.question', $faqs->skip(4)->first()->question)
            ->where('faqs.5.question', $faqs->skip(5)->first()->question)
        );
});
