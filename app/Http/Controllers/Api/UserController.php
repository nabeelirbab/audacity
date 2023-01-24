<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** @OA\Tag(
    *     name="User",
    *     description="API Endpoints of User"
    * )
*/
class UserController extends Controller
{

/** @OA\Post(
    *      path="/api/user",
    *      operationId="createUser",
    *      tags={"User"},
    *      summary="Create new user",
    *      security={{"sanctum":{}}},
    *      description="Create new user and return user data",
    *      @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/CreateUserRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/CreateUserRequest")
    *          )
    *      ),
    *      @OA\Response(
    *          response=201,
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
    public function create(CreateUserRequest $request)
    {
        $user = User::create($request->all());

        return response()->json($user, Response::HTTP_CREATED);
    }

/** @OA\Get(
    *      path="/api/user-by-email/{email}",
    *      operationId="getUserByEmail",
    *      tags={"User"},
    *      summary="Get user",
    *      security={{"sanctum":{}}},
    *      description="Return user data by email",
    *      @OA\Parameter(
    *          name="email",
    *          description="User EMail",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
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
    *          response=404,
    *          description="NotFound",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="User not found.")
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
    public function getByEmail(Request $request, string $email)
    {
        $user = User::with(['signal_subscriptions'])->whereEmail($email)->whereManagerId($request->user()->id)->first();

        if(!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_OK);
    }

/**
     * @OA\Delete(
     *      path="/api/user/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete existing user",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User Removed.")
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found.")
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
     *          response=500,
     *          description="Internal server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Internal server Error.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *          @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *          )
     *      )
     * )
*/
    public function delete(Request $request, $id)
    {

        $user = User::whereManagerId($request->user()->id)->findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User Removed']);
    }

/**
     * @OA\Put(
     *      path="/api/user/{id}",
     *      operationId="updateUser",
     *      tags={"User"},
     *      summary="Update existing user",
     *      description="Returns updated user data",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
    *       @OA\RequestBody(
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/UpdateUserRequest")
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::whereManagerId($request->user()->id)->findOrFail($id);

        $user->update($request->all());

        return response()->json($user, Response::HTTP_ACCEPTED);
    }


    public function debug(Request $request)
    {
        return response()->json(['message' => $request]);
    }
}