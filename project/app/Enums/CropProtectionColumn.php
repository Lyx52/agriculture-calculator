<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CropProtectionColumn: string implements HasLabel {
    case NAME = 'name';
    case PROTECTION_CATEGORY_CODES = 'protection_category_codes';
    case COMPANY = 'company';
    case DESCRIPTION = 'description';
    case UNIT_TYPE = 'unit_type';
    case COST_PER_UNIT = 'cost_per_unit';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::NAME => 'Nosaukums',
            self::PROTECTION_CATEGORY_CODES => 'Kategorijas',
            self::COMPANY => 'Īpašnieks',
            self::DESCRIPTION => 'Apraksts',
            self::UNIT_TYPE => 'Mērvienība',
            self::COST_PER_UNIT => 'Cena par mērvienību',
        };
    }
}
