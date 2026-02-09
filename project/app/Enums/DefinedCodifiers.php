<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use function Laravel\Prompts\select;

enum DefinedCodifiers: string implements HasLabel {
    case CROP_SPECIES = 'crop_species';
    case OPERATION_TYPES = 'operation_types';
    case CROP_PROTECTION_USAGE = 'crop_protection_usage';
    case AGRICULTURE_TECHNOLOGY = 'agriculture_technology';
    case AGRICULTURE_EQUIPMENT_TYPE = 'agriculture_equipment_type';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::CROP_SPECIES => 'Kūltūrauga suga',
            self::OPERATION_TYPES => 'Apstrādes operāciju veidi',
            self::CROP_PROTECTION_USAGE => 'Augu aizsardzības līdzekļa lietošanas kategorija',
            self::AGRICULTURE_TECHNOLOGY => 'Lauksaimniecības tehnoloģija',
            self::AGRICULTURE_EQUIPMENT_TYPE => 'Lauksaimniecība tehnikas veids'
        };
    }
}
