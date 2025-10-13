<?php

use App\Constants\Authorisation;
use App\Enums\AuthorisationStatus;
use App\Models\Cohort;
use App\Models\Teacher;
use App\Tasks\ExpireTeachers;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('expires teachers due for expiry', function () {
    // Seed authorised teachers due to expire today.
    Teacher::factory(2)
        ->authorised()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY)->timestamp)
        )
        ->create();

    Teacher::factory(2)
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY)->timestamp)
                ->create(),
        ])
        ->create();

    $teachers = Teacher::all();

    // Assert that they are all authorised
    $teachers->each(function (Teacher $teacher) {
        expect($teacher->currentAuthorisationStatus->value)
            ->toBe(AuthorisationStatus::Authorised);
    });

    // Run expire teachers task
    (new ExpireTeachers)();

    // Assert that they are all expired
    $teachers->fresh('currentAuthorisationStatus')
        ->each(function (Teacher $teacher) {
            expect($teacher->currentAuthorisationStatus->value)
                ->toBe(AuthorisationStatus::Expired);
        });

});

test('doesn\'t expire teachers not yet due for expiry', function () {
    // Seed authorised teachers not yet due for expiry.
    Teacher::factory(2)
        ->authorised()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY - 1)->timestamp)
        )
        ->create();

    Teacher::factory(2)
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

    $teachers = Teacher::all();

    // Assert that they are all authorised
    $teachers->each(function (Teacher $teacher) {
        expect($teacher->currentAuthorisationStatus->value)
            ->toBe(AuthorisationStatus::Authorised);
    });

    // Run expire teachers task
    (new ExpireTeachers)();

    // Assert that they are still all authorised
    $teachers->fresh('currentAuthorisationStatus')
        ->each(function (Teacher $teacher) {
            expect($teacher->currentAuthorisationStatus->value)
                ->toBe(AuthorisationStatus::Authorised);
        });

});

test('doesn\'t expire teachers no longer due for expiry', function () {
    // Seed authorised teachers that were previously, but are no longer due for expiry.
    Teacher::factory(2)
        ->authorised()
        ->has(
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY + 1)->timestamp)
        )
        ->create();

    Teacher::factory(2)
        ->authorised()
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

    $teachers = Teacher::all();

    // Assert that they are all authorised
    $teachers->each(function (Teacher $teacher) {
        expect($teacher->currentAuthorisationStatus->value)
            ->toBe(AuthorisationStatus::Authorised);
    });

    // Run expire teachers task
    (new ExpireTeachers)();

    // Assert that they are still all authorised
    $teachers->fresh('currentAuthorisationStatus')
        ->each(function (Teacher $teacher) {
            expect($teacher->currentAuthorisationStatus->value)
                ->toBe(AuthorisationStatus::Authorised);
        });

});
