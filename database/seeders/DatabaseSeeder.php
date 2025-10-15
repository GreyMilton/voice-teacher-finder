<?php

namespace Database\Seeders;

use App\Constants\Authorisation;
use App\Enums\CohortType;
use App\Models\AuthorisationStatus;
use App\Models\Cohort;
use App\Models\Faq;
use App\Models\Instrument;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $instruments = Instrument::inRandomOrder()
            ->limit(6)
            ->get();
        $languages = Language::inRandomOrder()
            ->limit(6)
            ->get();
        $territories = Territory::inRandomOrder()
            ->limit(10)
            ->get();

        $territories->each(function (Territory $territory) {
            TuitionLocation::factory(random_int(5, 10))
                ->for($territory)
                ->create();
        });

        $firstCohortTimestamp = now()->subYears(20)->timestamp;
        Cohort::factory()
            ->initialAuthorisation()
            ->date($firstCohortTimestamp)
            ->create();
        Cohort::factory(10)
            ->initialAuthorisation()
            ->between($firstCohortTimestamp, now()->subMonths(6)->timestamp)
            ->create();
        Cohort::factory()
            ->initialAuthorisation()
            ->after(now()->subMonths(6)->timestamp)
            ->create();
        $authorisationCohorts = Cohort::orderBy('completion_date', 'asc')->get();

        $authorisationCohorts->each(
            function (
                Cohort $authorisationCohort,
            ) use (
                $instruments,
                $languages,
                $territories,
            ) {
                $start = Carbon::parse($authorisationCohort->completion_date)->timestamp;
                Cohort::factory(2)
                    ->updateCohort()
                    ->after($start)
                    ->create();

                for ($i = 0; $i < 5; $i++) {
                    $this->seedTeacher(
                        $authorisationCohort,
                        $instruments,
                        $languages,
                        $territories,
                    );
                }
            });

        // Some unauthorised teachers.
        Teacher::factory(10)->create();

        User::factory()
            ->adminEmail('test')
            ->create(['name' => 'Test User']);

        Faq::factory(10)->create();
    }

    /**
     * Seed a teacher using the passed data.
     *
     * @param  Collection<int, Instrument>  $instruments
     * @param  Collection<int, Language>  $languages
     * @param  Collection<int, Territory>  $territories
     */
    private function seedTeacher(
        Cohort $authorisationCohort,
        Collection $instruments,
        Collection $languages,
        Collection $territories,
    ): void {
        $user = User::factory()
            ->has(
                Teacher::factory()
                    ->state(fn (array $attributes, ?Model $model) => [
                        'name' => $model instanceof User ? $model->name : '',
                        'territory_of_origin_id' => $territories->random()->id,
                        'territory_of_residence_id' => $territories->random()->id,
                    ])
            )
            ->create();

        $teacher = $user->teacher;

        $tuitionLocations = $teacher
            ->territoryOfResidence
            ->tuitionLocations
            ->random(random_int(1, 2));

        $teacher->instruments()
            ->sync($instruments->random(random_int(1, 5)));
        $teacher->languagesSung()
            ->sync($languages->random(random_int(1, 3)));
        $teacher->languagesTeachesIn()
            ->sync($languages->random(random_int(1, 3)));
        $teacher->tuitionLocations()
            ->sync($tuitionLocations);
        $cohorts = Cohort::whereCohortType(CohortType::Update)
            ->where('completion_date', '>', $authorisationCohort->completion_date)
            ->inRandomOrder()
            ->limit(random_int(1, 3))
            ->get();
        $cohorts->prepend($authorisationCohort);
        $teacher->cohorts()->sync($cohorts);

        if ($teacher->latestCohort->completion_date >= now()->subMonths(Authorisation::MONTHS_VALIDITY)) {
            $teacher->authorisationStatuses()->create(
                AuthorisationStatus::factory()
                    ->authorised()
                    ->make()
                    ->toArray()
            );
        } elseif ($teacher->latestCohort->completion_date < now()->subMonths(Authorisation::MONTHS_VALIDITY)) {
            $teacher->authorisationStatuses()->create(
                AuthorisationStatus::factory()
                    ->expired()
                    ->make()
                    ->toArray()
            );
        }
    }
}
