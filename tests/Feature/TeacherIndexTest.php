<?php

use App\Constants\Authorisation;
use App\Models\Cohort;
use App\Models\Teacher;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the teacher index', function () {
    $this->get(route('teacher.index'))
        ->assertStatus(200);
});

test('authenticated users can visit the teacher index', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('teacher.index'))
        ->assertStatus(200);
});

test('unauthorised teachers are hidden from the teacher index', function () {
    Teacher::factory(3)->create();

    $this
        ->get(route('teacher.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('TeacherIndex')
            ->has('teachers', 0)
        );
});

test('authorised teachers are visible on the teacher index', function () {
    // Just authorised teacher.
    Teacher::factory()
        ->authorised()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->timestamp)
        )
        ->create();

    // Recently updated teacher.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(1)->timestamp)
                ->create(),
        ])
        ->create();

    // Almost unauthorised teacher.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY - 1)->timestamp)
                ->create(),
        ])
        ->create();

    // Teacher after second update.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 3)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(1)->timestamp)
                ->create(),
        ])
        ->create();

    // Authorised teacher with no cohorts (e.g. manually authorised by admin).
    Teacher::factory()
        ->authorised()
        ->create();

    // Authorised teacher past cohort expiry (e.g. manually authorised by admin).
    Teacher::factory()
        ->authorised()
        ->expired()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 3)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
        ])
        ->create();

    $this
        ->get(route('teacher.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('TeacherIndex')
            ->has('teachers', 6)
        );
});

test('expired authorisation teachers are hidden from the teacher index', function () {
    // Authorisation just expired.
    Teacher::factory()
        ->authorised()
        ->expired()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY)->timestamp)
        )
        ->create();

    // Authorisation expired some time ago.
    Teacher::factory()
        ->authorised()
        ->expired()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 3)->timestamp)
        )
        ->create();

    // Updated authorisation expired.
    Teacher::factory()
        ->authorised()
        ->expired()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY + 1)->timestamp)
                ->create(),
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
