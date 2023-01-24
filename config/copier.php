<?php

use App\Enums\CopierRiskType;

return [

    'aval_risk_types' => env('AVAL_RISK_TYPES', implode(',',[
        CopierRiskType::MULTIPLIER->value,
        CopierRiskType::FIXED_LOT->value,
        CopierRiskType::MONEY_RATIO->value,
        CopierRiskType::RISK_PERCENT->value,
        CopierRiskType::SCALING->value
    ])),

    'has_adv_filters' =>  env('HAS_ADV_FILTER', false),
    'has_copy_existing' => env('HAS_COPY_EXISTING', true),
    'wait_to_verify' => env('WAIT_TO_VERIFY', 180),
];
