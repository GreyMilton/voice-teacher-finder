<?php

namespace App\Models;

use App\Enums\AuthorisationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The months of validity given to an authorisation or update.
     */
    private const int MONTHS_VALIDITY = 36;

    /**
     * The months of warning given to admins of an expiring authorisation / update.
     */
    private const int MONTHS_WARNING = 6;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = ['user'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gives_video_lessons' => 'boolean',
            'teaches_at_cvi' => 'boolean',
        ];
    }

    /**
     * Get the teacher's authorisation status.
     */
    protected function authorisationStatus(): Attribute
    {
        return Attribute::make(
            get: function (): AuthorisationStatus {
                if (! $this->latestTrainingDate) {
                    return AuthorisationStatus::Unauthorised;
                }

                $nextUpdate = Carbon::parse(
                    $this->latestTrainingDate,
                )->addMonths(self::MONTHS_VALIDITY);

                if ($nextUpdate < now()) {
                    return AuthorisationStatus::Unauthorised;
                }

                if ($nextUpdate < now()->addMonths(self::MONTHS_WARNING)) {
                    return AuthorisationStatus::Warning;
                }

                return AuthorisationStatus::Authorised;
            },
        );
    }

    /**
     * Get whether the teacher is curently authorised to teach.
     */
    protected function isAuthorised(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                return $this->authorisationStatus === AuthorisationStatus::Authorised
                    || $this->authorisationStatus === AuthorisationStatus::Warning;
            }
        );
    }

    /**
     * Get the teacher's latest training date from the later of:
     * - AuthorisationStatus date
     * - Latest update cohort date
     */
    protected function latestTrainingDate(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $authorisationDate = $this->authorisationCohort?->authorisation_date;
                $latestUpdateDate = $this->latestUpdateCohort?->course_date;

                if (! $authorisationDate && ! $latestUpdateDate) {
                    return null;
                }

                if (! $latestUpdateDate) {
                    return $authorisationDate;
                }

                if (! $authorisationDate) {
                    return $latestUpdateDate;
                }

                return $authorisationDate > $latestUpdateDate
                    ? $authorisationDate
                    : $latestUpdateDate;
            },
        );
    }

    /**
     * Get the teacher's authorisation cohort.
     */
    public function authorisationCohort(): BelongsTo
    {
        return $this->belongsTo(AuthorisationCohort::class);
    }

    /**
     * Get the teacher's country of origin.
     */
    public function countryOfOrigin(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_origin_id');
    }

    /**
     * Get the teacher's country of residence.
     */
    public function countryOfResidence(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_residence_id');
    }

    /**
     * Get the instruments the teacher plays.
     */
    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class)->withTimestamps();
    }

    /**
     * Get the languages the teacher sings in.
     */
    public function languagesSung(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_sung_teacher')
            ->withTimestamps();
    }

    /**
     * Get the languages the teacher gives lessons in.
     */
    public function languagesTeachesIn(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_taught_in_teacher')
            ->withTimestamps();
    }

    /**
     * Get the teacher's most recent update cohort.
     *
     * Uses `HasManyThrough` under the hood for the update cohorts relationship,
     * rather than the `BelongsToMany` 'updateCohorts' already defined on the model.
     * This is due to the existence of `HasOneThrough, yet
     * absence of `HasOneOfBelongsToMany` in Laravel.
     */
    public function latestUpdateCohort(): HasOneThrough
    {
        return $this
            ->hasManyThrough(
                UpdateCohort::class,
                TeacherUpdateCohort::class,
                'teacher_id',
                'id',
                'id',
                'update_cohort_id',
            )
            ->one()
            ->ofMany('course_date', 'max');
    }

    /**
     * Get the teacher's tuition locations (where they teach).
     */
    public function tuitionLocations(): BelongsToMany
    {
        return $this->belongsToMany(TuitionLocation::class)->withTimestamps();
    }

    /**
     * Get the teacher's update cohorts.
     */
    public function updateCohorts(): BelongsToMany
    {
        return $this->belongsToMany(UpdateCohort::class)
            ->withTimestamps();
    }

    /**
     * Get the teacher's user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
