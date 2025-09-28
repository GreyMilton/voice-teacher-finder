<?php

use App\Models\AuthorisationCohort;
use App\Models\Instrument;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\UpdateCohort;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the teacher show for an authorised teacher', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort(['cohort_date' => now()])
        ->create();

    $this
        ->assertGuest()
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(200);
});

test('guests can visit the teacher show for an updated teacher', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort([
            'cohort_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->hasUpdateCohorts(['cohort_date' => now()->subMonths(1)])
        ->create();

    $this
        ->assertGuest()
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(200);
});

test('authenticated users can visit the teacher show for an authorised teacher', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort(['cohort_date' => now()])
        ->create();

    $this
        ->actingAs(User::factory()->create())
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(200);
});

test('authenticated users can visit the teacher show for an updated teacher', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort([
            'cohort_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->hasUpdateCohorts(['cohort_date' => now()->subMonths(1)])
        ->create();

    $this
        ->actingAs(User::factory()->create())
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(200);
});

test('teacher show receives all required teacher data', function () {
    // Seed teacher and relationships
    $territories = Territory::inRandomOrder()
        ->limit(2)
        ->get();

    $teacher = Teacher::factory()
        ->forAuthorisationCohort(['cohort_date' => now()])
        ->hasUpdateCohorts(2)
        ->create([
            'territory_of_origin_id' => $territories->first()->id,
            'territory_of_residence_id' => $territories->first()->id,
        ]);

    $tuitionLocations = TuitionLocation::factory(2)
        ->for($teacher->territoryOfResidence)
        ->create();
    $teacher->tuitionLocations()
        ->sync($tuitionLocations);

    $instruments = Instrument::inRandomOrder()
        ->limit(2)
        ->get();
    $teacher->instruments()
        ->sync($instruments);

    $languages = Language::inRandomOrder()
        ->limit(3)
        ->get();
    $teacher->languagesSung()
        ->sync($languages->random(2));
    $teacher->languagesTeachesIn()
        ->sync($languages->random(2));

    // Ensure seeding was done correctly.
    expect($teacher->authorisationCohort)->toBeInstanceOf(AuthorisationCohort::class);
    expect($teacher->territoryOfOrigin)->toBeInstanceOf(Territory::class);
    expect($teacher->territoryOfResidence)->toBeInstanceOf(Territory::class);
    expect($teacher->instruments->first())->toBeInstanceOf(Instrument::class);
    expect($teacher->languagesSung->first())->toBeInstanceOf(Language::class);
    expect($teacher->languagesTeachesIn->first())->toBeInstanceOf(Language::class);
    expect($teacher->updateCohorts->first())->toBeInstanceOf(UpdateCohort::class);
    expect($teacher->tuitionLocations->first())->toBeInstanceOf(TuitionLocation::class);

    // Take route and assert passed data is correct, matching seeded data.
    $this
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('TeacherShow')
            ->has('teacher', fn (Assert $page) => $page
                ->where('id', $teacher->id)
                ->where('authorisationCohort', $teacher->authorisationCohort->name)
                ->where('business_email', $teacher->business_email)
                ->where('business_phone', $teacher->business_phone)
                ->where('business_website', $teacher->business_website)
                ->where('territoryOfOrigin', $teacher->territoryOfOrigin->english_name)
                ->where('territoryOfResidence', $teacher->territoryOfResidence->english_name)
                ->where('description', $teacher->description)
                ->where('gender', $teacher->gender->getLabel())
                ->where('gives_video_lessons', $teacher->gives_video_lessons)
                ->where('instruments', $teacher->instruments->pluck('english_name'))
                ->where('languagesSung', $teacher->languagesSung->pluck('english_name'))
                ->where('languagesTeachesIn', $teacher->languagesTeachesIn->pluck('english_name'))
                ->where('name', $teacher->name)
                ->where('profile_image_path', $teacher->profile_image_path)
                ->where('qualification_string', $teacher->qualification_string)
                ->where('teaches_at_cvi', $teacher->teaches_at_cvi)
                ->where('tuitionLocations', $teacher->tuitionLocations->map(
                    fn (TuitionLocation $location) => $location->title,
                ))
                ->where('updateCohorts', $teacher->updateCohorts->pluck('name'))
            )
        );
});

test('teacher show for a teacher that has not been authorised returns 404', function () {
    $teacher = Teacher::factory()->create();

    $this
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(404);
});

test('teacher show for teacher whose authorisation has expired returns 404', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort([
            'cohort_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->create();

    $this
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(404);
});

test('teacher show for teacher whose latest update has expired returns 404', function () {
    $teacher = Teacher::factory()
        ->forAuthorisationCohort([
            'cohort_date' => now()->subMonths(Teacher::MONTHS_VALIDITY * 2),
        ])
        ->hasUpdateCohorts([
            'cohort_date' => now()->subMonths(Teacher::MONTHS_VALIDITY + 1),
        ])
        ->create();

    $this
        ->get(route('teacher.show', ['teacher' => $teacher]))
        ->assertStatus(404);
});
