<?php
namespace App\Enums;

enum CropProtectionColumns: string {
    case COMPANY = 'company';
    case DESCRIPTION = 'description';
    case NAME = 'name';
    case PROTECTION_CATEGORY_CODES = 'protection_category_codes';
    case UNIT_TYPE = 'unit_type';
    case COST_PER_UNIT = 'cost_per_unit';
}
