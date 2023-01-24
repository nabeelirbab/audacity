<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** @OA\Tag(
    *     name="Account",
    *     description="API Endpoints of Account"
    * )
*/
class AccountController extends Controller
{

/** @OA\Post(
    *      path="/api/account",
    *      operationId="createAccount",
    *      tags={"Account"},
    *      summary="Create new account",
    *      security={{"sanctum":{}}},
    *      description="Create new account and return account data",
    *      @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/CreateAccountRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/CreateAccountRequest")
    *          )
    *      ),
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Account")
    *       ),
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
    *                        property="title",
    *                        type="array",
    *                        collectionFormat="multi",
    *                        @OA\Items(
    *                            type="string",
    *                            example={"The title field is required."}
    *                        )
    *                   )
    *              )
    *          )
    *     )
    * )
*/
    public function create(CreateAccountRequest $request)
    {
        $account = Account::create($request->all());

        return response()->json($account, Response::HTTP_CREATED);
    }

/**
    * @OA\Delete(
    *      path="/api/account/{id}",
    *      operationId="deleteAccount",
    *      tags={"Account"},
    *      summary="Delete existing account",
    *      description="Deletes a record and returns no content",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Account id",
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
    *              @OA\Property(property="message", type="string", example="Account Removed.")
    *          )
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Account not found",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Account not found.")
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

        $account = Account::whereManagerId($request->user()->id)->findOrFail($id);

        $account->delete();

        return response()->json(['message' => 'Account Removed']);
    }

/**
    * @OA\Put(
    *      path="/api/account/{id}",
    *      operationId="updateAccount",
    *      tags={"Account"},
    *      summary="Update existing account",
    *      description="Returns updated account data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Account id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/UpdateAccountRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/UpdateAccountRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Account")
    *       ),
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
    *             @OA\Property(property="message", type="string", example="Account not found.")
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
    *                        property="title",
    *                        type="array",
    *                        collectionFormat="multi",
    *                        @OA\Items(
    *                            type="string",
    *                            example={"The title field is required."}
    *                        )
    *                   )
    *              )
    *          )
    *     )
    * )
*/
    public function update(UpdateAccountRequest $request, $id)
    {
        $account = Account::whereManagerId($request->user()->id)->findOrFail($id);

        $account->update($request->all());

        return response()->json($account, Response::HTTP_ACCEPTED);
    }

}