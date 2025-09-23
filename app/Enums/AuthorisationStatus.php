<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AuthorisationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Authorised = 'Authorised';
    case Warning = 'Warning';
    case Unauthorised = 'Unauthorised';

    public function getColor(): string
    {
        return match ($this) {
            self::Authorised => 'success',
            self::Warning => 'warning',
            self::Unauthorised => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Authorised => 'heroicon-c-check-badge',
            self::Warning => 'heroicon-c-exclamation-triangle',
            self::Unauthorised => 'heroicon-c-x-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Authorised => 'Authorised',
            self::Warning => 'Near expiry',
            self::Unauthorised => 'Unauthorised',
        };
    }
}
