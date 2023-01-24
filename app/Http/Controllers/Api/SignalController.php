<?php

namespace App\Http\Controllers\Api;

use App\Enums\CopierType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSignalRequest;
use App\Http\Requests\ManageSignalFollowerRequest;
use App\Http\Requests\ManageSignalSenderRequest;
use App\Http\Requests\UpdateSignalRequest;
use App\Models\Account;
use App\Models\CopierSignal;
use App\Models\CopierSignalFollower;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** @OA\Tag(
    *     name="Signal",
    *     description="API Endpoints of Signal"
    * )
*/
class SignalController extends Controller
{

/** @OA\Post(
    *      path="/api/signal",
    *      operationId="createSignal",
    *      tags={"Signal"},
    *      summary="Create new signal",
    *      security={{"sanctum":{}}},
    *      description="Create new signal and return signal data",
    *      @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/CreateSignalRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/CreateSignalRequest")
    *          )
    *      ),
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    public function create(CreateSignalRequest $request)
    {
        $signal = CopierSignal::create($request->all());

        return response()->json($signal, Response::HTTP_CREATED);
    }

/**
     * @OA\Delete(
     *      path="/api/signal/{id}",
     *      operationId="deleteSignal",
     *      tags={"Signal"},
     *      summary="Delete existing signal",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Signal id",
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
     *              @OA\Property(property="message", type="string", example="Signal Removed.")
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Signal not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Signal not found.")
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

        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);

        $signal->delete();

        return response()->json(['message' => 'Signal Removed']);
    }

/**
    * @OA\Put(
    *      path="/api/signal/{id}",
    *      operationId="updateSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/UpdateSignalRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/UpdateSignalRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function update(UpdateSignalRequest $request, $id)
    {
        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);

        $signal->update($request->all());

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }

/**
    * @OA\Put(
    *      path="/api/signal-add-sender/{id}",
    *      operationId="addSenderSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal, add sender account",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageSignalSenderRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageSignalSenderRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function addSignalSender(ManageSignalSenderRequest $request, $id)
    {

        /** @var CopierSignal $signal */
        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);
        $account = Account
            ::whereManagerId($request->user()->id)
            ->whereCopierType(CopierType::SENDER)
            ->findOrFail($request->account_id);

        $signal->senders()->syncWithoutDetaching([$account->id]);
        $signal = CopierSignal::with(['senders'])->whereManagerId($request->user()->id)->findOrFail($id);

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }

/**
    * @OA\Put(
    *      path="/api/signal-remove-sender/{id}",
    *      operationId="removeSenderSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal, remove sender account",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageSignalSenderRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageSignalSenderRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function removeSignalSender(ManageSignalSenderRequest $request, $id)
    {

        /** @var CopierSignal $signal */
        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);
        $account = Account
            ::whereManagerId($request->user()->id)
            ->whereCopierType(CopierType::SENDER)
            ->findOrFail($request->account_id);

        $signal->senders()->detach($account, true);
        $signal = CopierSignal::with(['senders'])->whereManagerId($request->user()->id)->findOrFail($id);

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }

/**
    * @OA\Put(
    *      path="/api/signal-add-follower/{id}",
    *      operationId="addFollowerSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal, add follower account",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageSignalFollowerRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageSignalFollowerRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function addSignalFollower(ManageSignalFollowerRequest $request, $id)
    {

        /** @var CopierSignal $signal */
        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);
        $account = Account
            ::whereManagerId($request->user()->id)
            ->whereCopierType(CopierType::FOLLOWER)
            ->findOrFail($request->account_id);

        $follower = new CopierSignalFollower;

        $follower->account_id = $account->id;
        $follower->signal_id = $signal->id;
        $follower->save();

        $follower->fillSignalDefaults($request->all());

        $signal = CopierSignal::with(['followers:id'])->whereManagerId($request->user()->id)->findOrFail($id);

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }

/**
    * @OA\Put(
    *      path="/api/signal-remove-follower/{id}",
    *      operationId="removeFollowerSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal, remove follower account",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageSignalFollowerRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageSignalFollowerRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function removeSignalFollower(ManageSignalFollowerRequest $request, $id)
    {

        /** @var CopierSignal $signal */
        $signal = CopierSignal::whereManagerId($request->user()->id)->findOrFail($id);
        $account = Account
            ::whereManagerId($request->user()->id)
            ->whereCopierType(CopierType::FOLLOWER)
            ->findOrFail($request->account_id);

        $signal->followers()->detach($account);

        $signal = CopierSignal::with(['followers:id'])->whereManagerId($request->user()->id)->findOrFail($id);

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }

/**
    * @OA\Put(
    *      path="/api/signal-disable-follower/{id}",
    *      operationId="disableFollowerSignal",
    *      tags={"Signal"},
    *      summary="Update existing signal, disable follower account",
    *      description="Returns updated signal data",
    *      security={{"sanctum":{}}},
    *      @OA\Parameter(
    *          name="id",
    *          description="Signal id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *       @OA\RequestBody(
    *          @OA\JsonContent(ref="#/components/schemas/ManageSignalFollowerRequest"),
    *          @OA\MediaType(
    *               mediaType="multipart/form-data",
    *               @OA\Schema(ref="#/components/schemas/ManageSignalFollowerRequest")
    *          )
    *       ),
    *      @OA\Response(
    *          response=202,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CopierSignal")
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
    *             @OA\Property(property="message", type="string", example="Signal not found.")
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
    public function disableSignalFollower(ManageSignalFollowerRequest $request, $id)
    {

        CopierSignalFollower::where('signal_id', $id)->where('account_id', $request->account_id)
            ->update(['copier_enabled'=>0]);

        $signal = CopierSignal::with(['followers:id'])->whereManagerId($request->user()->id)->findOrFail($id);

        return response()->json($signal, Response::HTTP_ACCEPTED);
    }
    public function debug(Request $request)
    {
        return response()->json(['message' => $request]);
    }
}