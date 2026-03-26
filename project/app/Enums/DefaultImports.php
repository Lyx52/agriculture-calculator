<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DefaultImports: string implements HasLabel {
    case CROP_SPECIES = 'crop_species';
    case CROP_PROTECTION = 'crop_protection';
    case FERTILIZERS = 'fertilizers';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::FERTILIZERS => 'Aizsardzības mineralmēslojumi',
            self::CROP_PROTECTION => 'Augu aizsardzības līdzekļi',
            self::CROP_SPECIES => 'Skirnes katalogs',
        };
    }
}
