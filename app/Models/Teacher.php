<?php

namespace App\Models;

use App\Enums\AuthorisationStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $authorisation_cohort_id
 * @property string $business_email
 * @property string $business_phone
 * @property string $business_website
 * @property int|null $country_of_origin_id
 * @property int|null $country_of_residence_id
 * @property string $description
 * @property Gender $gender
 * @property bool $gives_video_lessons
 * @property string $name
 * @property string $profile_image_path
 * @property string $qualification_string
 * @property bool $teaches_at_cvi
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\AuthorisationCohort|null $authorisationCohort
 * @property-read AuthorisationStatus $authorisation_status
 * @property-read \App\Models\Country|null $countryOfOrigin
 * @property-read \App\Models\Country|null $countryOfResidence
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Instrument> $instruments
 * @property-read int|null $instruments_count
 * @property-read bool $is_authorised
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languagesSung
 * @property-read int|null $languages_sung_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languagesTeachesIn
 * @property-read int|null $languages_teaches_in_count
 * @property-read string|null $latest_training_date
 * @property-read \App\Models\UpdateCohort|null $latestUpdateCohort
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TuitionLocation> $tuitionLocations
 * @property-read int|null $tuition_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UpdateCohort> $updateCohorts
 * @property-read int|null $update_cohorts_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereAuthorisationCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereBusinessEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereBusinessPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereBusinessWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCountryOfOriginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCountryOfResidenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereGivesVideoLessons($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereProfileImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereQualificationString($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereTeachesAtCvi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereUserId($value)
 * @mixin \Eloquent
 */
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
            'gender' => Gender::class,
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
