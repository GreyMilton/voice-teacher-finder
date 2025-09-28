<?php

namespace Database\Seeders;

use App\Models\AuthorisationCohort;
use App\Models\Instrument;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\Territory;
use App\Models\TuitionLocation;
use App\Models\UpdateCohort;
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
        AuthorisationCohort::factory()
            ->date($firstCohortTimestamp)
            ->create();
        AuthorisationCohort::factory(10)
            ->between($firstCohortTimestamp, now()->subMonths(6)->timestamp)
            ->create();
        AuthorisationCohort::factory()
            ->after(now()->subMonths(6)->timestamp)
            ->create();
        $authorisationCohorts = AuthorisationCohort::orderBy('cohort_date', 'asc')->get();

        $authorisationCohorts->each(
            function (
                AuthorisationCohort $authorisationCohort,
            ) use (
                $instruments,
                $languages,
                $territories,
            ) {
                $start = Carbon::parse($authorisationCohort->cohort_date)->timestamp;
                UpdateCohort::factory(2)
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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Seed a teacher using the passed data.
     *
     * @param  Collection<int, Instrument>  $instruments
     * @param  Collection<int, Language>  $languages
     * @param  Collection<int, Territory>  $territories
     */
    private function seedTeacher(
        AuthorisationCohort $authorisationCohort,
        Collection $instruments,
        Collection $languages,
        Collection $territories,
    ): void {
        $user = User::factory()
            ->has(
                Teacher::factory()
                    ->state(fn (array $attributes, ?Model $model) => [
                        'authorisation_cohort_id' => $authorisationCohort->id,
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
        $teacher->updateCohorts()->sync(
            UpdateCohort::where('cohort_date', '>', $authorisationCohort->cohort_date)
                ->inRandomOrder()
                ->limit(random_int(1, 3))
                ->get()
        );
    }
}
