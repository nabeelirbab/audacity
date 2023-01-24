<?php

declare(strict_types=1);

namespace App\Http\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PerformancePlanResponse')]
class PerformancePlanResponse
{
    #[OA\Property(type: 'string')]
    public string $title;

    #[OA\Property(type: 'integer')]
    public int $id;
}
