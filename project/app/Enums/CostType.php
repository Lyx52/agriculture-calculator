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

    public function getMaterialTypeOptions(): array
    {
        return match ($this) {
            self::EUR_KILOGRAMS => MaterialAmountType::kilogramsOptions(),
            self::EUR_LITERS => MaterialAmountType::litersOptions(),
            default => [],
        };
    }

    public static function amountOptions(): array {
        return [
            self::EUR_HECTARES->value => self::EUR_HECTARES->getLabel(),
            self::EUR_LITERS->value => self::EUR_LITERS->getLabel(),
            self::EUR_KILOGRAMS->value => self::EUR_KILOGRAMS->getLabel(),
        ];
    }

    public static function workOptions(): array {
        return [
            self::EUR_HOUR->value => self::EUR_HOUR->getLabel(),
            self::EUR_HECTARES->value => self::EUR_HECTARES->getLabel(),
        ];
    }
}
