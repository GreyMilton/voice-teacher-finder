<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CohortType: string implements HasColor, HasIcon, HasLabel
{
    case InitialAuthorisation = 'InitialAuthorisation';
    case Update = 'Update';

    public function getColor(): string
    {
        return match ($this) {
            self::InitialAuthorisation => 'success',
            self::Update => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::InitialAuthorisation => 'heroicon-c-check-badge',
            self::Update => 'heroicon-c-check-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::InitialAuthorisation => 'Initial Authorisation',
            self::Update => 'Update',
        };
    }
}
