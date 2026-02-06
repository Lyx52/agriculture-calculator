<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CostType: string implements HasLabel {
    case EUR_HOUR = 'eur_hour';
    case EUR_HECTARES = 'eur_hectares';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::EUR_HOUR => "eur/h",
            self::EUR_HECTARES => "eur/ha",
        };
    }
}
