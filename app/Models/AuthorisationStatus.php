<?php

namespace App\Models;

use App\Enums\AuthorisationStatus as EnumsAuthorisationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $teacher_id
 * @property EnumsAuthorisationStatus $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\AuthorisationStatusFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationStatus whereValue($value)
 *
 * @mixin \Eloquent
 */
class AuthorisationStatus extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorisationStatusFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'teacher_id',
        'value',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => EnumsAuthorisationStatus::class,
        ];
    }
}
