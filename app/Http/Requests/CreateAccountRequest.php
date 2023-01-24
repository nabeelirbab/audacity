<?php

namespace App\Http\Requests;

use App\Enums\CopierType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *      title="Create Account request",
 *      description="Store Account request body data",
 *      type="object",
 *      required={"account_number", "password", "broker_server_name", "user_id"},
 *      @OA\Property(
 *          property="account_number",
 *          title="Account Number",
 *          format="integer",
 *          description="Account Number",
 *          example="123456"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          title="Account Password",
 *          description="Account Password",
 *          example="pass123"
 *      ),
 *      @OA\Property(
 *          property="broker_server_name",
 *          title="Broker Server Name",
 *          description="Broker Server Name",
 *          example="Alpari-Demo"
 *      ),
 *      @OA\Property(
 *          property="user_id",
 *          title="Account User",
 *          format="integer",
 *          description="Account User",
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="title",
 *          title="title",
 *          description="Account Title",
 *          example="Test Account"
 *      ),
 *      @OA\Property(
 *          property="copier_type",
 *          title="Copier Type",
 *          description="Copier Type",
 *          format="string",
 *          enum={"SENDER", "FOLLOWER"},
 *          example=""
 *      )
 * )
*/
class CreateAccountRequest extends FormRequest
{

    public function __construct(Request $request)
    {
        $request->merge([
            'copier_type' => $request->isEmptyString('copier_type')
                ? CopierType::SENDER
                : CopierType::fromCase($request->copier_type ),
            'manager_id' => $request->user()->id,
            'creator_id' => $request->user()->id
        ]);

    }

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_number' => 'required|unique:accounts',
            'broker_server_name' => 'required',
            'password' => 'required',
            'user_id' => 'required'
        ];
    }
}
