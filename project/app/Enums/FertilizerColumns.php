<?php
namespace App\Enums;

use Illuminate\Support\Collection;

enum FertilizerColumns: string {
    case NAME = 'name';
    case CONTENTS = 'contents';
    case COMPANY = 'company';
    case VALUE_N = 'value_n';
    case VALUE_P2O5 = 'value_p2o5';
    case VALUE_K2O = 'value_k2o';
    case VALUE_CA = 'value_ca';
    case VALUE_MG = 'value_mg';
    case VALUE_NA = 'value_na';
    case VALUE_S = 'value_s';
    case VALUE_B = 'value_b';
    case VALUE_CO = 'value_co';
    case VALUE_CU = 'value_cu';
    case VALUE_FE = 'value_fe';
    case VALUE_MN = 'value_mn';
    case VALUE_MO = 'value_mo';
    case VALUE_ZN = 'value_zn';
    case VALUE_CACO3 = 'value_caco3';
    case UNIT_TYPE = 'unit_type';
    case COST_PER_UNIT = 'cost_per_unit';

    public static function contentsColumns(): Collection {
        return collect([
            self::VALUE_N,
            self::VALUE_P2O5,
            self::VALUE_K2O,
            self::VALUE_CA,
            self::VALUE_MG,
            self::VALUE_NA,
            self::VALUE_S,
            self::VALUE_B,
            self::VALUE_CO,
            self::VALUE_CU,
            self::VALUE_FE,
            self::VALUE_MN,
            self::VALUE_MO,
            self::VALUE_ZN,
            self::VALUE_CACO3,
        ]);
    }
}
