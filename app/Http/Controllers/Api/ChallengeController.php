<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ChallengeResponse;
use App\Models\Challenge;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Challenge', description:'API Endpoints of Challenges')]
class ChallengeController extends Controller
{

    #[OA\Get(
        path: '/api/challenges',
        operationId: 'getChallenges',
        tags: ['Challenge'],
        description: 'List of Challenge',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Challenge',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: ChallengeResponse::class)
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Unauthenticated')]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Forbidden')]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Internal server Error.')]
                )
            ),
        ]
    )
    ]
    public function listAll(Request $request)
    {

        $challenges = Challenge::with(['plan', 'user'])->where('manager_id', $request->user()->id)
            ->get();

        $resp = $challenges->map(function(Challenge $challenge) {
            return new ChallengeResponse($challenge->user->email, $challenge->plan->title, $challenge->status);
        });

        return response()->json($resp, Response::HTTP_OK);
    }
}