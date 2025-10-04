<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AuthorisationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Unauthorised = 'Unauthorised';
    case Authorised = 'Authorised';
    case Expired = 'Expired';

    public function getColor(): string
    {
        return match ($this) {
            self::Unauthorised => 'gray',
            self::Authorised => 'success',
            self::Expired => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Unauthorised => 'heroicon-c-book-open',
            self::Authorised => 'heroicon-c-check-badge',
            self::Expired => 'heroicon-c-x-mark',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Unauthorised => 'Unauthorised',
            self::Authorised => 'Authorised',
            self::Expired => 'Expired',
        };
    }
}
