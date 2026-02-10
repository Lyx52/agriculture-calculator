<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UnitType: string implements HasLabel {
    case LITERS = 'l';
    case KILOGRAMS = 'kg';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LITERS => "l",
            self::KILOGRAMS => "kg",
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
            ]
        };
    }
}
