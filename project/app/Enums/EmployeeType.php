<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum EmployeeType: string implements HasLabel {
    case WORKER = 'worker';
    case EXTERNAL_SERVICE = 'external_service';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::WORKER => "Darbinieks",
            self::EXTERNAL_SERVICE => "Ārējais pakalpojums",
        };
    }
}
