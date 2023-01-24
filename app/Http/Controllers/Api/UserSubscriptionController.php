<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageUserSubscriptionRequest;
use App\Http\Requests\UpdateUserSubscriptionRequest;
use App\Models\User;
use App\Models\UserSetting;
use Symfony\Component\HttpFoundation\Response;

/** @OA\Tag(
    *     name="UserSubscription",
    *     description="API Endpoints of User Subscription"
    * )
*/
class UserSubscriptionController extends Controller
{

/** @OA\Put(
    *      path="/api/user-subscription-create",
    *      operationId="createUserSubscription",
    *      tags={"UserSubscription"},
    *      summary="Create new user subscription and return user data",
    *      security={{"sanctum":{}}},
    *      description="Create new user subscription and return user data",
    *      @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageUserSubscriptionRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageUserSubscriptionRequest")
    *          )
    *      ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/User")
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server Error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Internal server Error.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid."),
    *              @OA\Property(
    *                   property="errors",
    *                   type="object",
    *                   @OA\Property(
    *                        property="email",
    *                        type="array",
    *                        collectionFormat="multi",
    *                        @OA\Items(
    *                            type="string",
    *                            example={"The email field is required.","The email must be a valid email address."}
    *                        )
    *                   )
    *              )
    *          )
    *     )
    * )
*/
    public function create(ManageUserSubscriptionRequest $request)
    {
        $user = User::whereManagerId($request->user()->id)->findOrFail($request->user_id);

        $user->signals()->syncWithoutDetaching($request->signal_id);

        $user = User::with('signal_subscriptions')->whereManagerId($request->user()->id)->findOrFail($request->user_id);

        return response()->json($user, Response::HTTP_ACCEPTED);
    }

/** @OA\Put(
    *      path="/api/user-subscription-delete",
    *      operationId="deleteUserSubscription",
    *      tags={"UserSubscription"},
    *      summary="delete user subscription",
    *      security={{"sanctum":{}}},
    *      description="delete user subscription and return user data",
    *      @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageUserSubscriptionRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageUserSubscriptionRequest")
    *          )
    *      ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/User")
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server Error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Internal server Error.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid."),
    *              @OA\Property(
    *                   property="errors",
    *                   type="object",
    *                   @OA\Property(
    *                        property="email",
    *                        type="array",
    *                        collectionFormat="multi",
    *                        @OA\Items(
    *                            type="string",
    *                            example={"The email field is required.","The email must be a valid email address."}
    *                        )
    *                   )
    *              )
    *          )
    *     )
    * )
*/
    public function delete(ManageUserSubscriptionRequest $request)
    {
        $user = User::whereManagerId($request->user()->id)->findOrFail($request->user_id);

        $user->signals()->detach($request->signal_id);

        $user = User::with('signal_subscriptions')->whereManagerId($request->user()->id)->findOrFail($request->user_id);

        return response()->json($user, Response::HTTP_ACCEPTED);
    }
/**
     * @OA\Put(
     *      path="/api/user-subscription-update",
     *      operationId="updateUserSubscription",
     *      tags={"UserSubscription"},
     *      summary="Update existing user subscription",
     *      description="Returns updated user data",
     *      security={{"sanctum":{}}},
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/UpdateUserSubscriptionRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/UpdateUserSubscriptionRequest")
    *          )
    *       ),
    *       @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/User")
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server Error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Internal server Error.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Resource Not Found",
    *          @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="User not found.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid."),
    *              @OA\Property(
    *                   property="errors",
    *                   type="object",
    *                   @OA\Property(
    *                        property="email",
    *                        type="array",
    *                        collectionFormat="multi",
    *                        @OA\Items(
    *                            type="string",
    *                            example={"The email field is required.","The email must be a valid email address."}
    *                        )
    *                   )
    *              )
    *          )
    *     )
     * )
*/
    public function update(UpdateUserSubscriptionRequest $request)
    {
        UserSetting::updateOrCreate(
            ['user_id'=>$request->user_id],
            ['user_id'=>$request->user_id, 'max_accounts' => $request->max_accounts]);

        $user = User::with('setting')->find($request->user_id);

        return response()->json($user, Response::HTTP_ACCEPTED);
    }

}