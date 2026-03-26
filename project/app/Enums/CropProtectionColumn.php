<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CropProtectionColumn: string implements HasLabel {
    case NAME = 'name';
    case CATEGORY = 'category';
    case OWNER = 'owner';
    case NUMBER = 'number';
    case PROTECTION_CLASS = 'protection_class';
    case ACTIVE_SUBSTANCE = 'active_substance';
    case DEADLINE = 'deadline';
    case UNIT = 'unit';
    case PRICE = 'price';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::NAME => 'Nosaukums',
            self::CATEGORY => 'Kat.',
            self::OWNER => 'Īpašnieks',
            self::NUMBER => 'Nr',
            self::PROTECTION_CLASS => 'Kl.',
            self::ACTIVE_SUBSTANCE => 'Darb.viela',
            self::DEADLINE => 'Termiņš',
            self::UNIT => 'Mērvienība',
            self::PRICE => 'Cena',
        };
    }
}
