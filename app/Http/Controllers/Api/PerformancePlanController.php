<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\PerformancePlanResponse;
use App\Models\PerformancePlan;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Plan', description:'API Endpoints of Plans')]
class PerformancePlanController extends Controller
{

    //10|342JJAMnM4Ih8HJHP2nGRuTq3wXOBHHmFnG4vVqr

    #[OA\Get(
        path: '/api/plans',
        operationId: 'getPlans',
        tags: ['Plan'],
        description: 'List of Plans',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Plans',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: PerformancePlanResponse::class)
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

        $plans = PerformancePlan::where('manager_id', $request->user()->id)->enabled()->get(['id','title']);

        return response()->json($plans, Response::HTTP_OK);
    }
}