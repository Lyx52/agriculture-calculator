<?php
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum FertilizerColumn: string implements HasLabel {
    case NAME = 'name';
    case CONTENTS = 'contents';
    case COMPANY = 'company';
    case N = 'value_n';
    case P2O5 = 'value_p2o5';
    case K2O = 'value_k2o';
    case CA = 'value_ca';
    case MG = 'value_mg';
    case NA = 'value_na';
    case S = 'value_s';
    case B = 'value_b';
    case CO = 'value_co';
    case CU = 'value_cu';
    case FE = 'value_fe';
    case MN = 'value_mn';
    case MO = 'value_mo';
    case ZN = 'value_zn';
    case CACO3 = 'value_caco3';
    case UNIT_TYPE = 'unit_type';
    case COST_PER_UNIT = 'cost_per_unit';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::NAME => 'Nosaukums',
            self::CONTENTS => 'Saturs',
            self::COMPANY => 'Īpašnieks',
            self::N => 'Saturs n',
            self::P2O5 => 'Saturs p2o5',
            self::K2O => 'Saturs k2o',
            self::CA => 'Saturs ca',
            self::MG => 'Saturs mg',
            self::NA => 'Saturs na',
            self::S => 'Saturs s',
            self::B => 'Saturs b',
            self::CO => 'Saturs co',
            self::CU => 'Saturs cu',
            self::FE => 'Saturs fe',
            self::MN => 'Saturs mn',
            self::MO => 'Saturs mo',
            self::ZN => 'Saturs zn',
            self::CACO3 => 'Saturs caco3',
            self::UNIT_TYPE => 'Mērvienība',
            self::COST_PER_UNIT => 'Cena par mērvienību',
        };
    }
}
