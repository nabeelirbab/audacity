<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\ChallengeStatus;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChallengeResponse')]
class ChallengeResponse
{

    public function __construct(
        #[OA\Property(type: 'string')]
        public string $email,
        #[OA\Property(type: 'string')]
        public string $plan,
        #[OA\Property(type: 'int', enum: ChallengeStatus::class)]
        public ChallengeStatus $status ) {
    }
}
