<?php

namespace App\Models;

use App\Enums\AuthorisationStatus;
use App\Enums\CohortType;
use App\Enums\Gender;
use App\Models\AuthorisationStatus as ModelsAuthorisationStatus;
use Database\Factories\TeacherFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $business_email
 * @property string|null $business_phone
 * @property string|null $business_website
 * @property string|null $description
 * @property Gender $gender
 * @property bool $gives_video_lessons
 * @property string $name
 * @property string|null $profile_image_path
 * @property string|null $qualification_string
 * @property bool $teaches_at_cvi
 * @property int|null $territory_of_origin_id
 * @property int|null $territory_of_residence_id
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ModelsAuthorisationStatus> $authorisationStatuses
 * @property-read int|null $authorisation_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cohort> $cohorts
 * @property-read int|null $cohorts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cohort> $cohortsHasManyThrough
 * @property-read int|null $cohorts_has_many_through_count
 * @property-read ModelsAuthorisationStatus|null $currentAuthorisationStatus
 * @property-read \App\Models\Cohort|null $firstAuthorisationCohort
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cohort> $initialAuthorisationCohorts
 * @property-read int|null $initial_authorisation_cohorts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Instrument> $instruments
 * @property-read int|null $instruments_count
 * @property-read bool $is_almost_authorisation_expired
 * @property-read bool $is_authorised
 * @property-read bool $is_visible
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languagesSung
 * @property-read int|null $languages_sung_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languagesTeachesIn
 * @property-read int|null $languages_teaches_in_count
 * @property-read \App\Models\Cohort|null $latestCohort
 * @property-read \App\Models\Cohort|null $latestUpdateCohort
 * @property-read \App\Models\Territory|null $territoryOfOrigin
 * @property-read \App\Models\Territory|null $territoryOfResidence
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TuitionLocation> $tuitionLocations
 * @property-read int|null $tuition_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cohort> $updateCohorts
 * @property-read int|null $update_cohorts_count
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
 * @method static Builder<static>|Teacher newModelQuery()
 * @method static Builder<static>|Teacher newQuery()
 * @method static Builder<static>|Teacher orderByCurrentAuthorisationStatus(string $direction = 'asc')
 * @method static Builder<static>|Teacher orderByFirstAuthorisationCohort(string $column, string $direction = 'asc')
 * @method static Builder<static>|Teacher orderByLatestUpdateCohort(string $column, string $direction = 'asc')
 * @method static Builder<static>|Teacher query()
 * @method static Builder<static>|Teacher visible()
 * @method static Builder<static>|Teacher whereBusinessEmail($value)
 * @method static Builder<static>|Teacher whereBusinessPhone($value)
 * @method static Builder<static>|Teacher whereBusinessWebsite($value)
 * @method static Builder<static>|Teacher whereCreatedAt($value)
 * @method static Builder<static>|Teacher whereDescription($value)
 * @method static Builder<static>|Teacher whereGender($value)
 * @method static Builder<static>|Teacher whereGivesVideoLessons($value)
 * @method static Builder<static>|Teacher whereId($value)
 * @method static Builder<static>|Teacher whereName($value)
 * @method static Builder<static>|Teacher whereProfileImagePath($value)
 * @method static Builder<static>|Teacher whereQualificationString($value)
 * @method static Builder<static>|Teacher whereTeachesAtCvi($value)
 * @method static Builder<static>|Teacher whereTerritoryOfOriginId($value)
 * @method static Builder<static>|Teacher whereTerritoryOfResidenceId($value)
 * @method static Builder<static>|Teacher whereUpdatedAt($value)
 * @method static Builder<static>|Teacher whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Teacher extends Model
{
    /**
     * @use HasFactory<TeacherFactory>
     */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'business_email',
        'business_phone',
        'business_website',
        'description',
        'gender',
        'gives_video_lessons',
        'name',
        'profile_image_path',
        'qualification_string',
        'teaches_at_cvi',
        'territory_of_origin_id',
        'territory_of_residence_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
     * Get whether the teacher's authorisation is almost expired.
     *
     * @return Attribute<bool, null>
     */
    protected function isAlmostAuthorisationExpired(): Attribute
    {
        return Attribute::make(get: function (): bool {
            $expirationDate = $this->latestCohort
                ->completion_date
                ->addMonths(Cohort::MONTHS_VALIDITY);

            return $expirationDate > now()
                && $expirationDate < now()->addMonths(Cohort::MONTHS_WARNING);
        });
    }

    /**
     * Get whether the teacher is currently authorised to teach.
     *
     * @return Attribute<bool, null>
     */
    protected function isAuthorised(): Attribute
    {
        return Attribute::make(get: function (): bool {
            return $this->currentAuthorisationStatus->value === AuthorisationStatus::Authorised;
        });
    }

    /**
     * Get whether the teacher's listing is publicly visible and searchable.
     *
     * @return Attribute<bool, null>
     */
    protected function isVisible(): Attribute
    {
        return Attribute::make(get: fn (): bool => $this->isAuthorised);
    }

    /**
     * Scope a query to only include teachers permitted to be publicly visible.
     *
     * @param  Builder<Teacher>  $query
     */
    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->whereHas('currentAuthorisationStatus', fn ($query) => $query
            ->where('value', AuthorisationStatus::Authorised)
        );
    }

    /**
     * Order teachers by a column on their first authorisation cohort.
     *
     * @param  Builder<Teacher>  $query
     * @return Builder<Teacher>
     */
    #[Scope]
    protected function orderByFirstAuthorisationCohort(
        Builder $query,
        string $column,
        string $direction = 'asc',
    ): Builder {
        return $query->orderBy(
            Cohort::select("cohorts.$column")
                ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
                ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
                ->where('cohorts.cohort_type', CohortType::InitialAuthorisation)
                ->orderBy('cohorts.completion_date', 'asc')
                ->limit(1),
            $direction
        );
    }

    /**
     * Order teachers by a column on their latest update cohort.
     *
     * @param  Builder<Teacher>  $query
     * @return Builder<Teacher>
     */
    #[Scope]
    protected function orderByLatestUpdateCohort(
        Builder $query,
        string $column,
        string $direction = 'asc',
    ): Builder {
        return $query->orderBy(
            Cohort::select("cohorts.$column")
                ->join('cohort_teacher', 'cohort_teacher.cohort_id', '=', 'cohorts.id')
                ->whereColumn('cohort_teacher.teacher_id', 'teachers.id')
                ->where('cohorts.cohort_type', CohortType::Update)
                ->orderBy('cohorts.completion_date', 'desc')
                ->limit(1),
            $direction
        );
    }

    /**
     * Get the teacher's current authorisation status.
     *
     * @return HasOne<ModelsAuthorisationStatus, $this>
     */
    public function currentAuthorisationStatus(): HasOne
    {
        return $this->hasOne(ModelsAuthorisationStatus::class)->latestOfMany();
    }

    /**
     * Order teachers by their current authorisation status.
     *
     * @param  Builder<Teacher>  $query
     * @return Builder<Teacher>
     */
    #[Scope]
    protected function orderByCurrentAuthorisationStatus(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy(
            ModelsAuthorisationStatus::select('value')
                ->whereColumn('teacher_id', 'teachers.id')
                ->latest('id'),
            $direction
        );
    }

    /**
     * Get all of the teacher's authorisation statuses.
     *
     * @return HasMany<ModelsAuthorisationStatus, $this>
     */
    public function authorisationStatuses(): HasMany
    {
        return $this->hasMany(ModelsAuthorisationStatus::class);
    }

    /**
     * Get the teacher's territory of origin.
     *
     * @return BelongsTo<Territory, $this>
     */
    public function territoryOfOrigin(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_of_origin_id');
    }

    /**
     * Get the teacher's territory of residence.
     *
     * @return BelongsTo<Territory, $this>
     */
    public function territoryOfResidence(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_of_residence_id');
    }

    /**
     * Get the instruments the teacher plays.
     *
     * @return BelongsToMany<Instrument, $this>
     */
    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class)->withTimestamps();
    }

    /**
     * Get the languages the teacher sings in.
     *
     * @return BelongsToMany<Language, $this>
     */
    public function languagesSung(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_sung_teacher')
            ->withTimestamps();
    }

    /**
     * Get the languages the teacher gives lessons in.
     *
     * @return BelongsToMany<Language, $this>
     */
    public function languagesTeachesIn(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_taught_in_teacher')
            ->withTimestamps();
    }

    /**
     * Get the teacher's tuition locations (where they teach).
     *
     * @return BelongsToMany<TuitionLocation, $this>
     */
    public function tuitionLocations(): BelongsToMany
    {
        return $this->belongsToMany(TuitionLocation::class)->withTimestamps();
    }

    /**
     * Get all the cohorts for the teacher.
     *
     * @return BelongsToMany<Cohort, $this>
     */
    public function cohorts(): BelongsToMany
    {
        return $this->belongsToMany(Cohort::class)
            ->withTimestamps();
    }

    /**
     * Get all the cohorts for the teacher using a 'HasManyThrough' relationship,
     * using a pivot model.
     *
     * This relationship has been created in order to provide a basis for 'HasOneThrough'
     * relationships on this model.
     *
     * It is necessary since 'HasOneOfBelongsToMany' does not exist in Laravel,
     * though otherwise should probably be avoided, and the 'cohorts'
     * 'BelongsToMany' relationship used wherever possible.
     *
     * @return HasManyThrough<Cohort, CohortTeacher, $this>
     */
    public function cohortsHasManyThrough(): HasManyThrough
    {
        return $this->hasManyThrough(
            Cohort::class,
            CohortTeacher::class,
            'teacher_id',
            'id',
            'id',
            'cohort_id',
        );
    }

    /**
     * Get the teacher's initial authorisation cohorts.
     *
     * The teacher will usually only belong to one initial authorisation cohort,
     * but the system has been designed to accommodate for the possibility of repeat training.
     *
     * @return BelongsToMany<Cohort, $this>
     */
    public function initialAuthorisationCohorts(): BelongsToMany
    {
        return $this->cohorts()
            ->whereCohortType(CohortType::InitialAuthorisation);
    }

    /**
     * Get the teacher's update cohorts.
     *
     * @return BelongsToMany<Cohort, $this>
     */
    public function updateCohorts(): BelongsToMany
    {
        return $this->cohorts()->whereCohortType(CohortType::Update);
    }

    /**
     * Get the teacher's first authorisation cohort.
     *
     * @return HasOneThrough<Cohort, CohortTeacher ,$this>
     */
    public function firstAuthorisationCohort(): HasOneThrough
    {
        return $this->cohortsHasManyThrough()
            ->whereCohortType(CohortType::InitialAuthorisation)
            ->one()
            ->ofMany('completion_date', 'min');
    }

    /**
     * Get the teacher's most recent update cohort.
     *
     * @return HasOneThrough<Cohort, CohortTeacher ,$this>
     */
    public function latestUpdateCohort(): HasOneThrough
    {
        return $this->cohortsHasManyThrough()
            ->whereCohortType(CohortType::Update)
            ->one()
            ->ofMany('completion_date', 'max');
    }

    /**
     * Get the teacher's most recent cohort.
     *
     * @return HasOneThrough<Cohort, CohortTeacher ,$this>
     */
    public function latestCohort(): HasOneThrough
    {
        return $this->cohortsHasManyThrough()
            ->one()
            ->ofMany('completion_date', 'max');
    }

    /**
     * Get the teacher's user account.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
