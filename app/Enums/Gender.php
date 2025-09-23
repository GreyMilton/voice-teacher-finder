<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasColor, HasLabel
{
    case Male = 'male';
    case Female = 'female';
    case NonBinary = 'non-binary';
    case PreferNotToSay = 'prefer-not-to-say';

    public function getColor(): string
    {
        return match ($this) {
            self::Male => 'cyan',
            self::Female => 'pink',
            self::NonBinary => 'violet',
            self::PreferNotToSay => 'gray',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::NonBinary => 'Non-binary',
            self::PreferNotToSay => 'Prefer Not To Say',
        };
    }

    public static function random(): Gender
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }
}
