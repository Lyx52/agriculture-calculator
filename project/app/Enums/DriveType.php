<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DriveType: string implements HasLabel {
    case DRIVE_4WD = 'drive_4wd';
    case DRIVE_2WD = 'drive_2wd';
    case DRIVE_MFWD = 'drive_mfwd';
    case DRIVE_CHAIN = 'drive_chain';
    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::DRIVE_4WD => "4x4",
            self::DRIVE_2WD => "2x4",
            self::DRIVE_MFWD => "MFWD",
            self::DRIVE_CHAIN => "Ķēžu",
        };
    }
}
