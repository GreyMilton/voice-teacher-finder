<?php

use App\Models\Teacher;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the teacher index', function () {
    $response = $this->get(route('teacher.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the teacher index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('teacher.index'));
    $response->assertStatus(200);
});

test('authorised teachers are visible on the teacher index', function () {
    // Just authorised teacher.
    Teacher::factory()
        ->forAuthorisationCohort(['authorisation_date' => now()])
        ->create();

    // Recently updated teacher.
    Teacher::factory()
        ->forAuthorisationCohort([
            'authorisation_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 2),
        ])
        ->hasUpdateCohorts([
            'course_date' => now()->subMonths(1),
        ])
        ->create();

    // Almost unauthorised teacher.
    Teacher::factory()
        ->forAuthorisationCohort([
            'authorisation_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 2),
        ])
        ->hasUpdateCohorts([
            'course_date' => now()->subMonths(Teacher::MONTHS_VALIDITY - 1),
        ])
        ->create();

    // Teacher after second update.
    Teacher::factory()
        ->forAuthorisationCohort([
            'authorisation_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 3),
        ])
        ->hasUpdateCohorts([
            'course_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 2),
        ])
        ->hasUpdateCohorts([
            'course_date' => now()->subMonths(1),
        ])
        ->create();

    $this
        ->get(route('teacher.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('TeacherIndex')
            ->has('teachers', 4)
        );
});

test('unauthorised teachers are hidden from the teacher index', function () {
    // Authorisation expired.
    Teacher::factory()
        ->forAuthorisationCohort([
            'authorisation_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->create();

    // Updated authorisation expired.
    Teacher::factory()
        ->forAuthorisationCohort([
            'authorisation_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 2),
        ])
        ->hasUpdateCohorts([
            'course_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->create();

    $this
        ->get(route('teacher.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('TeacherIndex')
            ->has('teachers', 0)
        );
});
