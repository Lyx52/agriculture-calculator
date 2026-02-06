<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DefaultImports: string {
    case CROP_SPECIES = 'crop_species';
    case CROP_PROTECTION = 'crop_protection';
}
