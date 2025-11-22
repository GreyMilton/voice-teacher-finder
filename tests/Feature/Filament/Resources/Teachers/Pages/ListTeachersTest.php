<?php

use App\Constants\Authorisation;
use App\Enums\AuthorisationStatus;
use App\Enums\CohortType;
use App\Enums\Gender;
use App\Filament\Resources\Teachers\Pages\ListTeachers;
use App\Models\AuthorisationStatus as ModelsAuthorisationStatus;
use App\Models\Cohort;
use App\Models\Instrument;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teachers = Teacher::factory(random_int(1, 10))->create();

    livewire(ListTeachers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($teachers);
});

test('admin can access page', function () {
    Teacher::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListTeachers::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Teacher::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListTeachers::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('table contains required columns', function () {
    livewire(ListTeachers::class)
        ->assertTableColumnExists('name')
        ->assertTableColumnExists('isExpiringToday')
        ->assertTableColumnExists('isNearAuthorisationExpiry')
        ->assertTableColumnExists('currentAuthorisationStatus.value')
        ->assertTableColumnExists('firstAuthorisationCohort.name')
        ->assertTableColumnExists('latestUpdateCohort.name')
        ->assertTableColumnExists('teaches_at_cvi')
        ->assertTableColumnExists('territoryOfResidence.english_name')
        ->assertTableColumnExists('territoryOfOrigin.english_name')
        ->assertTableColumnExists('gender')
        ->assertTableColumnExists('qualification_string')
        ->assertTableColumnExists('business_email')
        ->assertTableColumnExists('business_phone')
        ->assertTableColumnExists('business_website')
        ->assertTableColumnExists('description')
        ->assertTableColumnExists('gives_video_lessons')
        ->assertTableColumnExists('profile_image_path')
        ->assertTableColumnExists('user.name')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('updated_at');
});

test('table columns display the correct values for a given teacher', function () {
    // Seed teacher and relationships
    $territories = Territory::inRandomOrder()
        ->limit(2)
        ->get();

    $teacher = Teacher::factory()
        ->authorised()
        ->for(User::factory()->create())
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY + 1)->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(1)->timestamp)
                ->create(),
        ])
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
    expect($teacher->firstAuthorisationCohort)->toBeInstanceOf(Cohort::class);
    expect($teacher->territoryOfOrigin)->toBeInstanceOf(Territory::class);
    expect($teacher->territoryOfResidence)->toBeInstanceOf(Territory::class);
    expect($teacher->instruments->first())->toBeInstanceOf(Instrument::class);
    expect($teacher->languagesSung->first())->toBeInstanceOf(Language::class);
    expect($teacher->languagesTeachesIn->first())->toBeInstanceOf(Language::class);
    expect($teacher->updateCohorts->first())->toBeInstanceOf(Cohort::class);
    expect($teacher->tuitionLocations->first())->toBeInstanceOf(TuitionLocation::class);

    livewire(ListTeachers::class)
        ->assertTableColumnStateSet('name', $teacher->name, record: $teacher)
        ->assertTableColumnStateNotSet('name', 'Something Else', record: $teacher)
        ->assertTableColumnStateSet(
            'isExpiringToday',
            $teacher->isExpiringToday ? 'Expiring today' : 'No',
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'isExpiringToday',
            $teacher->isExpiringToday ? 'No' : 'Expiring today',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'isNearAuthorisationExpiry',
            $teacher->isNearAuthorisationExpiry ? 'Near expiry' : 'No',
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'isNearAuthorisationExpiry',
            $teacher->isNearAuthorisationExpiry ? 'No' : 'Near expiry',
            record: $teacher,
        )
        ->assertTableColumnFormattedStateSet(
            'currentAuthorisationStatus.value',
            $teacher->currentAuthorisationStatus->value->getLabel(),
            record: $teacher,
        )
        ->assertTableColumnFormattedStateNotSet(
            'currentAuthorisationStatus.value',
            $teacher->currentAuthorisationStatus->value === AuthorisationStatus::Authorised
                ? AuthorisationStatus::Expired->getLabel()
                : AuthorisationStatus::Authorised->getLabel(),
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'firstAuthorisationCohort.name',
            $teacher->firstAuthorisationCohort->name,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'firstAuthorisationCohort.name',
            'A bogus name',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'latestUpdateCohort.name',
            $teacher->latestUpdateCohort->name,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'latestUpdateCohort.name',
            'A bogus name',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'teaches_at_cvi',
            $teacher->teaches_at_cvi,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'teaches_at_cvi',
            ! $teacher->teaches_at_cvi,
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'territoryOfResidence.english_name',
            $teacher->territoryOfResidence->english_name,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'territoryOfResidence.english_name',
            'A bogus name',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'territoryOfOrigin.english_name',
            $teacher->territoryOfOrigin->english_name,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'territoryOfOrigin.english_name',
            'A bogus name',
            record: $teacher,
        )
        ->assertTableColumnFormattedStateSet(
            'gender',
            $teacher->gender->getLabel(),
            record: $teacher,
        )
        ->assertTableColumnFormattedStateNotSet(
            'gender',
            $teacher->gender === Gender::PreferNotToSay
                ? Gender::NonBinary->getLabel()
                : Gender::PreferNotToSay->getLabel(),
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'qualification_string',
            $teacher->qualification_string,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'qualification_string',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'business_email',
            $teacher->business_email,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'business_email',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'business_phone',
            $teacher->business_phone,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'business_phone',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'business_website',
            $teacher->business_website,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'business_website',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'description',
            $teacher->description,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'description',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'gives_video_lessons',
            $teacher->gives_video_lessons,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'gives_video_lessons',
            ! $teacher->gives_video_lessons,
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'profile_image_path',
            $teacher->profile_image_path,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'profile_image_path',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet(
            'user.name',
            $teacher->user->name,
            record: $teacher,
        )
        ->assertTableColumnStateNotSet(
            'user.name',
            'Something Else',
            record: $teacher,
        )
        ->assertTableColumnStateSet('created_at', $teacher->created_at, record: $teacher)
        ->assertTableColumnStateNotSet('created_at', now()->subMillennia(), record: $teacher)
        ->assertTableColumnStateSet('updated_at', $teacher->updated_at, record: $teacher)
        ->assertTableColumnStateNotSet('updated_at', now()->subMillennia(), record: $teacher);
});

test('table columns have correct initial visibility', function () {
    livewire(ListTeachers::class)
        // initially visible columns
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('isExpiringToday')
        ->assertCanRenderTableColumn('isNearAuthorisationExpiry')
        ->assertCanRenderTableColumn('currentAuthorisationStatus.value')
        ->assertCanRenderTableColumn('firstAuthorisationCohort.name')
        ->assertCanRenderTableColumn('latestUpdateCohort.name')
        ->assertCanRenderTableColumn('teaches_at_cvi')
        ->assertCanRenderTableColumn('territoryOfResidence.english_name')
        ->assertCanRenderTableColumn('territoryOfOrigin.english_name')
        ->assertCanRenderTableColumn('gender')
        ->assertCanRenderTableColumn('qualification_string')
        ->assertCanRenderTableColumn('business_email')
        ->assertCanRenderTableColumn('business_phone')
        ->assertCanRenderTableColumn('business_website')
        ->assertCanRenderTableColumn('description')
        ->assertCanRenderTableColumn('gives_video_lessons')
        ->assertCanRenderTableColumn('profile_image_path')
        ->assertCanRenderTableColumn('user.name')
        // initially hidden columns
        ->assertCanNotRenderTableColumn('created_at')
        ->assertCanNotRenderTableColumn('updated_at');
});

test("table 'name' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $name = Teacher::inRandomOrder()->first()->name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('name', "%{$name}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('name', "%{$name}%")->get(),
        );
});

test("table 'currentAuthorisationStatus.value' columns are searchable", function () {
    Teacher::factory(random_int(2, 5))->authorised()->create();
    Teacher::factory(random_int(2, 5))->expired()->create();
    $value = Teacher::inRandomOrder()->first()->currentAuthorisationStatus->value->value;

    livewire(ListTeachers::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords(
            Teacher::whereHas(
                'currentAuthorisationStatus',
                fn (Builder $query) => $query->whereLike('value', "%{$value}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereHas(
                'currentAuthorisationStatus',
                fn (Builder $query) => $query->whereNotLike('value', "%{$value}%"),
            )->get(),
        );
});

test("table 'firstAuthorisationCohort.name' columns are searchable", function () {
    // Create teachers with initial authorisation cohorts.
    // 1. Recently authorised teacher.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(1)->timestamp)
                ->create(['name' => 'test-one']),
        ])->create();
    // 2. Authorised a within the last year.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(2, 12))->timestamp)
                ->create(['name' => 'test-two']),
        ])->create();
    // 4. Authorised over a year ago, but still authorised (not yet expired).
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(12, 24))->timestamp)
                ->create(['name' => 'test-three']),
        ])->create();
    // 4. Authorised a while ago, but still authorised (not yet expired) - just.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY - 1)->timestamp)
                ->create(['name' => 'test-four']),
        ])->create();
    // 5. Authorised some time ago and now expired.
    Teacher::factory()
        ->authorised()
        ->expired()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(['name' => 'test-five']),
        ])->create();

    $name = Teacher::inRandomOrder()
        ->first()
        ->firstAuthorisationCohort
        ->name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereHas(
                'firstAuthorisationCohort',
                fn (Builder $query): Builder => $query
                    ->whereLike('name', "%{$name}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereHas(
                'firstAuthorisationCohort',
                fn (Builder $query): Builder => $query
                    ->whereNotLike('name', "%{$name}%"),
            )->get(),
        );
});

test("table 'latestUpdateCohort.name' columns are searchable", function () {
    // Create teachers with update cohorts.
    // 1. Authorised a within the last year, and updated a month ago.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(2, 12))->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(1)->timestamp)
                ->create(['name' => 'test-one']),
        ])->create();
    // 2. Authorised over a year ago and updated recently.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(12, 24))->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(random_int(1, 3))->timestamp)
                ->create(['name' => 'test-two']),
        ])->create();
    // 3. Authorised a while ago and updated twice.
    Teacher::factory()
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
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(random_int(1, 6))->timestamp)
                ->create(['name' => 'test-three']),
        ])->create();
    // 4. Authorised and updated a while ago, and now expired.
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
                ->create(['name' => 'test-four']),
        ])->create();

    $name = Teacher::has('latestUpdateCohort')
        ->inRandomOrder()
        ->first()
        ->latestUpdateCohort
        ->name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereHas(
                'latestUpdateCohort',
                fn (Builder $query): Builder => $query
                    ->whereLike('name', "%{$name}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereHas(
                'latestUpdateCohort',
                fn (Builder $query): Builder => $query
                    ->whereNotLike('name', "%{$name}%"),
            )->get(),
        );
});

test("table 'territoryOfResidence.english_name' columns are searchable", function () {
    $territories = Territory::inRandomOrder()
        ->limit(10)
        ->get();

    Teacher::factory()->create([
        'territory_of_residence_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_residence_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_residence_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_residence_id' => $territories->random()->id,
    ]);

    $name = Teacher::inRandomOrder()->first()->territoryOfResidence->english_name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereHas('territoryOfResidence', fn (Builder $query): Builder => $query
                ->whereLike('english_name', "%{$name}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotHas('territoryOfResidence', fn (Builder $query): Builder => $query
                ->whereLike('english_name', "%{$name}%"),
            )->get(),
        );
});

test("table 'territoryOfOrigin.english_name' columns are searchable", function () {
    $territories = Territory::inRandomOrder()
        ->limit(10)
        ->get();

    Teacher::factory()->create([
        'territory_of_origin_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_origin_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_origin_id' => $territories->random()->id,
    ]);
    Teacher::factory()->create([
        'territory_of_origin_id' => $territories->random()->id,
    ]);

    $name = Teacher::inRandomOrder()->first()->territoryOfOrigin->english_name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereHas('territoryOfOrigin', fn (Builder $query): Builder => $query
                ->whereLike('english_name', "%{$name}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotHas('territoryOfOrigin', fn (Builder $query): Builder => $query
                ->whereLike('english_name', "%{$name}%"),
            )->get(),
        );
});

test("table 'gender' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $gender = Teacher::inRandomOrder()->first()->gender->value;

    livewire(ListTeachers::class)
        ->searchTable($gender)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('gender', "%{$gender}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('gender', "%{$gender}%")->get(),
        );
});

test("table 'qualification_string' columns are searchable", function () {
    Teacher::factory()->create([
        'qualification_string' => 'Bachelor of Music, Master of Arts in Vocal Pedagogy',
    ]);
    Teacher::factory()->create([
        'qualification_string' => 'Doctor of Musical Arts, Bachelor of Education',
    ]);
    Teacher::factory()->create([
        'qualification_string' => 'Master of Music, Bachelor of Fine Arts',
    ]);
    Teacher::factory()->create([
        'qualification_string' => 'Bachelor of Arts in Music, Master of Science in Voice Therapy',
    ]);

    $qualification_string = Teacher::inRandomOrder()
        ->first()
        ->qualification_string;

    livewire(ListTeachers::class)
        ->searchTable($qualification_string)
        ->assertCanSeeTableRecords(
            Teacher::whereLike(
                'qualification_string',
                "%{$qualification_string}%",
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike(
                'qualification_string',
                "%{$qualification_string}%",
            )->get(),
        );
});

test("table 'business_email' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $business_email = Teacher::inRandomOrder()->first()->business_email;

    livewire(ListTeachers::class)
        ->searchTable($business_email)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('business_email', "%{$business_email}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('business_email', "%{$business_email}%")->get(),
        );
});

test("table 'business_phone' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $business_phone = Teacher::inRandomOrder()->first()->business_phone;

    livewire(ListTeachers::class)
        ->searchTable($business_phone)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('business_phone', "%{$business_phone}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('business_phone', "%{$business_phone}%")->get(),
        );
});

test("table 'business_website' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $business_website = Teacher::inRandomOrder()->first()->business_website;

    livewire(ListTeachers::class)
        ->searchTable($business_website)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('business_website', "%{$business_website}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('business_website', "%{$business_website}%")->get(),
        );
});

test("table 'description' columns are searchable", function () {
    Teacher::factory(random_int(5, 10))->create();
    $description = Teacher::inRandomOrder()->first()->description;

    livewire(ListTeachers::class)
        ->searchTable($description)
        ->assertCanSeeTableRecords(
            Teacher::whereLike('description', "%{$description}%")->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereNotLike('description', "%{$description}%")->get(),
        );
});

test("table 'user.name' columns are searchable", function () {
    User::factory(5)
        ->has(Teacher::factory())
        ->create();

    $name = Teacher::inRandomOrder()->first()->user->name;

    livewire(ListTeachers::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords(
            Teacher::whereHas('user', fn ($query) => $query
                ->whereLike('name', "%{$name}%"),
            )->get(),
        )
        ->assertCanNotSeeTableRecords(
            Teacher::whereHas('user', fn ($query) => $query
                ->whereNotLike('name', "%{$name}%"),
            )->get(),
        );
});

test("table can be sorted by 'name'", function () {
    Teacher::factory(5)->create();

    $teachersSortedAsc = Teacher::orderBy('name')->get();
    $teachersSortedDesc = Teacher::orderBy('name', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'currentAuthorisationStatus.value'", function () {
    Teacher::factory()->create(); // unauthorised
    Teacher::factory()->authorised()->create();
    Teacher::factory()->expired()->create();

    $teachersSortedAsc = Teacher::orderBy(
        ModelsAuthorisationStatus::select('value')
            ->whereColumn('teacher_id', 'teachers.id')
            ->latest('id')
    )->get();
    $teachersSortedDesc = Teacher::orderByDesc(
        ModelsAuthorisationStatus::select('value')
            ->whereColumn('teacher_id', 'teachers.id')
            ->latest('id')
    )->get();

    livewire(ListTeachers::class)
        ->sortTable('currentAuthorisationStatus.value')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('currentAuthorisationStatus.value', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'firstAuthorisationCohort.name'", function () {
    // Create teachers with initial authorisation cohorts.
    // 1. Recently authorised teacher.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(1)->timestamp)
                ->create(['name' => 'test-one']),
        ])->create();
    // 2. Authorised a within the last year.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(2, 12))->timestamp)
                ->create(['name' => 'test-two']),
        ])->create();
    // 4. Authorised over a year ago, but still authorised (not yet expired).
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(12, 24))->timestamp)
                ->create(['name' => 'test-three']),
        ])->create();
    // 4. Authorised a while ago, but still authorised (not yet expired) - just.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY - 1)->timestamp)
                ->create(['name' => 'test-four']),
        ])->create();
    // 5. Authorised some time ago and now expired.
    Teacher::factory()
        ->authorised()
        ->expired()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(Authorisation::MONTHS_VALIDITY * 2)->timestamp)
                ->create(['name' => 'test-five']),
        ])->create();

    $teachersSortedAsc = Teacher::orderBy(
        Cohort::select('cohorts.name')
            ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
            ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
            ->where('cohorts.cohort_type', CohortType::InitialAuthorisation)
            ->orderBy('cohorts.completion_date', 'asc')
            ->limit(1)
    )->get();
    $teachersSortedDesc = Teacher::orderByDesc(
        Cohort::select('cohorts.name')
            ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
            ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
            ->where('cohorts.cohort_type', CohortType::InitialAuthorisation)
            ->orderBy('cohorts.completion_date', 'asc')
            ->limit(1)
    )->get();

    livewire(ListTeachers::class)
        ->sortTable('firstAuthorisationCohort.name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('firstAuthorisationCohort.name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);

});

test("table can be sorted by 'latestUpdateCohort.name'", function () {
    // Create teachers with update cohorts.
    // 1. Authorised a within the last year, and updated a month ago.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(2, 12))->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(1)->timestamp)
                ->create(['name' => 'test-one']),
        ])->create();
    // 2. Authorised over a year ago and updated recently.
    Teacher::factory()
        ->authorised()
        ->hasAttached([
            Cohort::factory()
                ->initialAuthorisation()
                ->date(now()->subMonths(random_int(12, 24))->timestamp)
                ->create(),
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(random_int(1, 3))->timestamp)
                ->create(['name' => 'test-two']),
        ])->create();
    // 3. Authorised a while ago and updated twice.
    Teacher::factory()
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
            Cohort::factory()
                ->updateCohort()
                ->date(now()->subMonths(random_int(1, 6))->timestamp)
                ->create(['name' => 'test-three']),
        ])->create();
    // 4. Authorised and updated a while ago, and now expired.
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
                ->create(['name' => 'test-four']),
        ])->create();

    $teachersSortedAsc = Teacher::orderBy(
        Cohort::select('cohorts.name')
            ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
            ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
            ->where('cohorts.cohort_type', CohortType::Update)
            ->orderBy('cohorts.completion_date', 'desc')
            ->limit(1)
    )->get();
    $teachersSortedDesc = Teacher::orderByDesc(
        Cohort::select('cohorts.name')
            ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
            ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
            ->where('cohorts.cohort_type', CohortType::Update)
            ->orderBy('cohorts.completion_date', 'desc')
            ->limit(1)
    )->get();

    livewire(ListTeachers::class)
        ->sortTable('latestUpdateCohort.name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('latestUpdateCohort.name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'teaches_at_cvi'", function () {
    Teacher::factory()->create(['teaches_at_cvi' => true]);
    Teacher::factory()->create(['teaches_at_cvi' => false]);

    $teachersSortedAsc = Teacher::orderBy('teaches_at_cvi')->get();
    $teachersSortedDesc = Teacher::orderBy('teaches_at_cvi', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('teaches_at_cvi')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('teaches_at_cvi', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'territoryOfResidence.english_name'", function () {
    $territories = Territory::inRandomOrder()->take(5)->get();

    Teacher::factory(5)
        ->create()
        ->each(fn ($teacher, $i) => $teacher->update([
            'territory_of_residence_id' => $territories[$i]->id,
        ]));

    $teachersSortedAsc = Teacher::with('territoryOfResidence')
        ->orderBy(
            Territory::select('english_name')
                ->whereColumn('territories.id', 'teachers.territory_of_residence_id')
        )
        ->get();
    $teachersSortedDesc = Teacher::with('territoryOfResidence')
        ->orderByDesc(
            Territory::select('english_name')
                ->whereColumn('territories.id', 'teachers.territory_of_residence_id')
        )
        ->get();

    livewire(ListTeachers::class)
        ->sortTable('territoryOfResidence.english_name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('territoryOfResidence.english_name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'territoryOfOrigin.english_name'", function () {
    $territories = Territory::inRandomOrder()->take(5)->get();

    Teacher::factory(5)
        ->create()
        ->each(fn ($teacher, $i) => $teacher->update([
            'territory_of_origin_id' => $territories[$i]->id,
        ]));

    $teachersSortedAsc = Teacher::with('territoryOfOrigin')
        ->orderBy(
            Territory::select('english_name')
                ->whereColumn('territories.id', 'teachers.territory_of_origin_id')
        )
        ->get();
    $teachersSortedDesc = Teacher::with('territoryOfOrigin')
        ->orderByDesc(
            Territory::select('english_name')
                ->whereColumn('territories.id', 'teachers.territory_of_origin_id')
        )
        ->get();

    livewire(ListTeachers::class)
        ->sortTable('territoryOfOrigin.english_name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('territoryOfOrigin.english_name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'gender'", function () {
    Teacher::factory()->create(['gender' => Gender::Female]);
    Teacher::factory()->create(['gender' => Gender::Male]);
    Teacher::factory()->create(['gender' => Gender::NonBinary]);
    Teacher::factory()->create(['gender' => Gender::PreferNotToSay]);

    $teachersSortedAsc = Teacher::orderBy('gender')->get();
    $teachersSortedDesc = Teacher::orderBy('gender', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('gender')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('gender', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'qualification_string'", function () {
    Teacher::factory(5)->create();
    $teachersSortedAsc = Teacher::orderBy('qualification_string')->get();
    $teachersSortedDesc = Teacher::orderBy('qualification_string', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('qualification_string')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('qualification_string', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'business_email'", function () {
    Teacher::factory(5)->create();
    $teachersSortedAsc = Teacher::orderBy('business_email')->get();
    $teachersSortedDesc = Teacher::orderBy('business_email', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('business_email')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('business_email', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'business_phone'", function () {
    Teacher::factory(5)->create();
    $teachersSortedAsc = Teacher::orderBy('business_phone')->get();
    $teachersSortedDesc = Teacher::orderBy('business_phone', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('business_phone')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('business_phone', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'business_website'", function () {
    Teacher::factory(5)->create();
    $teachersSortedAsc = Teacher::orderBy('business_website')->get();
    $teachersSortedDesc = Teacher::orderBy('business_website', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('business_website')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('business_website', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'gives_video_lessons'", function () {
    Teacher::factory()->create(['gives_video_lessons' => true]);
    Teacher::factory()->create(['gives_video_lessons' => false]);

    $teachersSortedAsc = Teacher::orderBy('gives_video_lessons')->get();
    $teachersSortedDesc = Teacher::orderBy('gives_video_lessons', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('gives_video_lessons')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('gives_video_lessons', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'user.name'", function () {
    User::factory(5)
        ->has(Teacher::factory())
        ->create();
    $teachersSortedAsc = Teacher::with('user')
        ->orderBy(
            User::select('name')
                ->whereColumn('users.id', 'teachers.user_id')
        )
        ->get();
    $teachersSortedDesc = Teacher::with('user')
        ->orderByDesc(
            User::select('name')
                ->whereColumn('users.id', 'teachers.user_id')
        )
        ->get();

    livewire(ListTeachers::class)
        ->sortTable('user.name')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('user.name', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'created_at'", function () {
    Teacher::factory()->create(['created_at' => now()->subMinutes(2)]);
    Teacher::factory()->create(['created_at' => now()->subMinute()]);
    Teacher::factory()->create();
    $teachersSortedAsc = Teacher::orderBy('created_at')->get();
    $teachersSortedDesc = Teacher::orderBy('created_at', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table can be sorted by 'updated_at'", function () {
    Teacher::factory()->create(['updated_at' => now()->subMinutes(2)]);
    Teacher::factory()->create(['updated_at' => now()->subMinute()]);
    Teacher::factory()->create();
    $teachersSortedAsc = Teacher::orderBy('updated_at')->get();
    $teachersSortedDesc = Teacher::orderBy('updated_at', 'desc')->get();

    livewire(ListTeachers::class)
        ->sortTable('updated_at')
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true)
        ->sortTable('updated_at', 'desc')
        ->assertCanSeeTableRecords($teachersSortedDesc, inOrder: true);
});

test("table default sorting is 'name' in ascending order", function () {
    Teacher::factory(random_int(5, 10))->create();
    $teachersSortedAsc = Teacher::orderBy('name')->get();

    livewire(ListTeachers::class)
        ->assertCanSeeTableRecords($teachersSortedAsc, inOrder: true);
});
