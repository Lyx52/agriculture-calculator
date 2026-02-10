<?php

use App\Models\User;

if (!function_exists('user')) {
    function user(): User {
        return auth()->user();
    }
}

if (!function_exists('generate_fertilizer_name')) {
    function generate_fertilizer_name(array $data): string {
        $parts = [];
        $n = floatval($data['value_n'] ?? 0);
        $p = floatval($data['value_p2o5'] ?? 0);
        $k = floatval($data['value_k2o'] ?? 0);
        $hasN = $n > 0;
        $hasP = $p > 0;
        $hasK = $k > 0;

        if ($hasN && $hasP && $hasK) {
            $parts[] = "NPK ($n-$p-$k)";
        } elseif (!$hasN && $hasP && $hasK) {
            $parts[] = "PK ($p-$k)";
        } elseif (!$hasN && $hasP && !$hasK) {
            $parts[] = "(P)-$p";
        } elseif (!$hasN && !$hasP && $hasK) {
            $parts[] = "(K)-$k";
        } elseif ($hasN && $hasP && !$hasK) {
            $parts[] = "NP ($n-$p)";
        } elseif ($hasN && !$hasP && $hasK) {
            $parts[] = "NK ($n-$k)";
        } elseif ($hasN && !$hasP && !$hasK) {
            $parts[] = "(N)-$n";
        }

        $microMap = [
            'value_ca' => 'Ca', 'value_mg' => 'Mg', 'value_na' => 'Na', 'value_s' => 'S',
            'value_b' => 'B',  'value_co' => 'Co', 'value_cu' => 'Cu', 'value_fe' => 'Fe',
            'value_mn' => 'Mn', 'value_mo' => 'Mo', 'value_zn' => 'Zn', 'value_caco' => 'CaCo'
        ];

        foreach ($microMap as $fieldName => $label) {
            $value = floatval($data[$fieldName] ?? 0);
            if ($value > 0) {
                $parts[] = "($label)-$value";
            }
        }

        return implode(' ', $parts);
    }
}
