<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum OtherMaterialType: string implements HasLabel {
    case WATER = 'water';
    case MANURE = 'manure';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::WATER => "Ūdens",
            self::MANURE => "Kūtsmēsli",
        };
    }

    public function amountOptions(): array {
        return match ($this) {
            self::WATER => [
                MaterialAmountType::LITERS_PER_HECTARE->value => MaterialAmountType::LITERS_PER_HECTARE->getLabel(),
                MaterialAmountType::LITERS_TOTAL->value => MaterialAmountType::LITERS_TOTAL->getLabel(),
            ],
            default => collect(MaterialAmountType::cases())->pluck(fn($value) => $value->getLabel(), 'value')->toArray(),
        };
    }
}
