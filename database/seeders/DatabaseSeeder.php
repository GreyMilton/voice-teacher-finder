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
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $territories = Territory::inRandomOrder()
            ->limit(10)
            ->get();

        $territories->each(function (Territory $territory) {
            TuitionLocation::factory(mt_rand(5, 10))
                ->for($territory)
                ->create();
        });

        $authorisationCohorts = AuthorisationCohort::factory(5)->create();
        $instruments = Instrument::inRandomOrder()
            ->limit(6)
            ->get();
        $languages = Language::inRandomOrder()
            ->limit(6)
            ->get();
        $updateCohorts = UpdateCohort::factory(10)->create();

        $users = User::factory(10)
            ->has(
                Teacher::factory()
                    ->state(function (array $attributes, ?Model $model) {
                        return $model instanceof User ? ['name' => $model->name] : [];
                    })
                    ->sequence(
                        fn () => [
                            'authorisation_cohort_id' => $authorisationCohorts->random()->id,
                            'territory_of_origin_id' => $territories->random()->id,
                            'territory_of_residence_id' => $territories->random()->id,
                        ],
                    )
            )
            ->create();

        $users->each(function (User $user) use ($instruments, $languages, $updateCohorts) {
            $teacher = $user->teacher;

            $tuitionLocations = $teacher
                ->territoryOfResidence
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
