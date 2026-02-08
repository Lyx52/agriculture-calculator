<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CostType: string implements HasLabel {
    case EUR_HOUR = 'eur_hour';
    case EUR_HECTARES = 'eur_hectares';
    case EUR_KILOGRAMS = 'eur_kilograms';
    case EUR_LITERS = 'eur_liters';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::EUR_HOUR => "eur/h",
            self::EUR_KILOGRAMS => "eur/kg",
            self::EUR_LITERS => "eur/l",
            self::EUR_HECTARES => "eur/ha",
        };
    }
}
