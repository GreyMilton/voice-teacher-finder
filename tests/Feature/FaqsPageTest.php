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
