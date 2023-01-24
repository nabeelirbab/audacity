<?php

namespace App\Http\Controllers\Api;

use App\Enums\ChallengeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserChallengeRequest;
use App\Http\Requests\DisableUserChallengeRequest;
use App\Http\Responses\ChallengeResponse;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use App\Models\User;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;


#[OA\Tag(name: 'Challenge', description:'API Endpoints of Challenges')]
class UserChallengeController extends Controller
{

    #[OA\Post(
        path: '/api/user-add-challenge',
        operationId: 'addUserChallnege',
        tags: ['Challenge'],
        description: 'Add User to Challenge',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(type: AddUserChallengeRequest::class)
            )]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Challenge',
                content: new OA\JsonContent(type: ChallengeResponse::class)
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
                response: 404,
                description: 'Not Found',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Plan not found')]
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
    public function add(AddUserChallengeRequest $request)
    {
        $plan = PerformancePlan::find($request['plan_id']);

        if(is_null($plan))
            return response()->json(['message'=>'Plan not found'], Response::HTTP_NOT_FOUND);

        $userCheck = User::where('username', '=', $request['email'])->first();

        if(!$userCheck) {
            $user = User::create([
                'username'             => $request['email'],
                'email'                => $request['email'],
                'name'                 => $request['name'],
                'manager_id'           => $request['manager_id'],
                'creator_id'           => $request['creator_id'],

                'password'             => bcrypt(Str::random(40)),
                'api_token'            => Str::random(12),
                'activated'            => true,
                'signup_sm_ip'         => $request->getClientIp(),
            ]);
        } else {
            $user = $userCheck;
        }

        $challenge = new Challenge();
        $challenge->performance_plan_id = $request['plan_id'];
        $challenge->user_id = $user->id;
        $challenge->manager_id = $request['manager_id'];
        $challenge->status = ChallengeStatus::CONFIRMED;
        $challenge->save();

        $resp = new ChallengeResponse($user->email, $plan->title, ChallengeStatus::CONFIRMED);

        return response()->json($resp, Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/user-disable-challenge',
        operationId: 'disableUserChallnege',
        tags: ['Challenge'],
        description: 'Disable Challenge',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(type: DisableUserChallengeRequest::class)
            )]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Challenge disabled',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'message', type: 'string', example:'ok')])
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
                response: 404,
                description: 'Not Found',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example:'Plan not found')]
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
    public function disable(DisableUserChallengeRequest $request)
    {
        $ids = $request['ids'];

        if($ids == 'all')
            $plans = PerformancePlan::where('manager_id', $request['manager_id'])->enabled()->get();
        else {
            $ids = explode(',', $ids);
            $plans = PerformancePlan::find($ids);
        }

        foreach($plans as $plan) {

            $user = User::where('username', '=', $request['email'])->first();
            if(is_null($user))
                return response()->json(['message'=>'User not found'], Response::HTTP_OK);

            Challenge
                ::where('user_id',$user->id)
                ->where('performance_plan_id',$plan->id)
                ->update(['status'=> ChallengeStatus::ENDED]);
        }
        return response()->json(['message'=>'ok'], Response::HTTP_OK);
    }
}