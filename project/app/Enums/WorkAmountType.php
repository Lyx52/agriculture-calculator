<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum WorkAmountType: string implements HasLabel {
    case LITERS = 'liters';
    case KILOGRAMS = 'kilograms';
    case METERS = 'meters';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LITERS => "l",
            self::KILOGRAMS => "kg",
            self::METERS => "m",
        };
    }
}
