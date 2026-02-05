<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DefinedCodifiers: string implements HasLabel {
    case CROP_TYPES = 'crop_types';
    case OPERATION_TYPES = 'operation_types';
    case COST_TYPES = 'cost_types';
    case EMPLOYEE_TYPES = 'employee_types';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::CROP_TYPES => 'Kūltūras veidi',
            self::OPERATION_TYPES => 'Apstrādes operāciju veidi',
            self::COST_TYPES => 'Izmaksu veidi',
            self::EMPLOYEE_TYPES => 'Darbinieka veids'
        };
    }
}
