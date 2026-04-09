<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum MaterialAmountType: string implements HasLabel {
    case LITERS_PER_HECTARE = 'liters_per_hectare';
    case KILOGRAMS_PER_HECTARE = 'kilograms_per_hectare';
    case KILOGRAMS_TOTAL = 'kilograms_total';
    case LITERS_TOTAL = 'liters_total';
    case GRAMS_PER_HECTARE = 'grams_per_hectare';
    case GRAMS_TOTAL = 'grams_total';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LITERS_PER_HECTARE => "l/ha",
            self::LITERS_TOTAL => "l kopā",
            self::KILOGRAMS_PER_HECTARE => "kg/ha",
            self::KILOGRAMS_TOTAL => "kg kopā",
            self::GRAMS_PER_HECTARE => "g/ha",
            self::GRAMS_TOTAL => "g kopā",
        };
    }

    public static function kilogramsOptions(): array {
        return [
            self::KILOGRAMS_PER_HECTARE->value => self::KILOGRAMS_PER_HECTARE->getLabel(),
        ];
    }

    public static function litersOptions(): array {
        return [
            self::LITERS_PER_HECTARE->value => self::LITERS_PER_HECTARE->getLabel(),
        ];
    }

    public function unitType(): UnitType {
        return match ($this) {
            self::LITERS_PER_HECTARE, self::LITERS_TOTAL => UnitType::LITERS,
            self::KILOGRAMS_PER_HECTARE, self::KILOGRAMS_TOTAL => UnitType::KILOGRAMS,
            self::GRAMS_PER_HECTARE, self::GRAMS_TOTAL => UnitType::GRAMS,
        };
    }

    public function isPerHectare(): bool {
        return in_array($this, [self::LITERS_PER_HECTARE, self::KILOGRAMS_PER_HECTARE, self::GRAMS_PER_HECTARE]);
    }

}
