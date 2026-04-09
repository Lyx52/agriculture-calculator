<?php
namespace App\Enums;
use Exception;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UnitType: string implements HasLabel {
    case LITERS = 'l';
    case KILOGRAMS = 'kg';
    case GRAMS = 'g';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LITERS => "l",
            self::KILOGRAMS => "kg",
            self::GRAMS => 'g',
        };
    }

    public function convertTo(float|int $value, UnitType $convertedTo): float {
        return match ($this) {
            self::GRAMS => match($convertedTo) {
                self::GRAMS => $value,
                self::KILOGRAMS => $value / 1000
            },
            self::KILOGRAMS => match($convertedTo) {
                self::GRAMS => $value * 1000,
                self::KILOGRAMS => $value
            },
            default => $value
        };
    }

    public function amountOptions(): array {
        return match ($this) {
            self::LITERS => [
                MaterialAmountType::LITERS_PER_HECTARE->value => MaterialAmountType::LITERS_PER_HECTARE->getLabel(),
                MaterialAmountType::LITERS_TOTAL->value => MaterialAmountType::LITERS_TOTAL->getLabel(),
            ],
            self::KILOGRAMS =>  [
                MaterialAmountType::KILOGRAMS_PER_HECTARE->value => MaterialAmountType::KILOGRAMS_PER_HECTARE->getLabel(),
                MaterialAmountType::KILOGRAMS_TOTAL->value => MaterialAmountType::KILOGRAMS_TOTAL->getLabel(),
            ],
            self::GRAMS => [
                MaterialAmountType::GRAMS_PER_HECTARE->value => MaterialAmountType::GRAMS_PER_HECTARE->getLabel(),
                MaterialAmountType::GRAMS_TOTAL->value => MaterialAmountType::GRAMS_TOTAL->getLabel(),
            ]
        };
    }
}
