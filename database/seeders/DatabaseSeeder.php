<?php

namespace Database\Seeders;

use App\Models\AuthorisationCohort;
use App\Models\Country;
use App\Models\Instrument;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\UpdateCohort;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $countries = Country::factory(10)
            ->hasTuitionLocations(mt_rand(5, 10))
            ->create();
        $authorisationCohorts = AuthorisationCohort::factory(5)->create();
        $instruments = Instrument::factory(5)->create();
        $languages = Language::factory(5)->create();
        $updateCohorts = UpdateCohort::factory(10)->create();

        $users = User::factory(10)
            ->has(
                Teacher::factory()
                    ->state(function (array $attributes, User $user) {
                        return ['name' => $user->name];
                    })
                    ->sequence(
                        fn () => [
                            'authorisation_cohort_id' => $authorisationCohorts->random()->id,
                            'country_of_origin_id' => $countries->random()->id,
                            'country_of_residence_id' => $countries->random()->id,
                        ],
                    )
            )
            ->create();

        $users->each(function (User $user) use ($instruments, $languages, $updateCohorts) {
            $teacher = $user->teacher;

            $tuitionLocations = $teacher
                ->countryOfResidence
                ->tuitionLocations
                ->random(mt_rand(1, 2));

            $teacher->instruments()
                ->sync($instruments->random(mt_rand(1, 5)));
            $teacher->languagesSung()
                ->sync($languages->random(mt_rand(1, 3)));
            $teacher->languagesTeachesIn()
                ->sync($languages->random(mt_rand(1, 3)));
            $teacher->tuitionLocations()
                ->sync($tuitionLocations);
            $teacher->updateCohorts()
                ->sync($updateCohorts->random(mt_rand(1, 3)));
        });

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
