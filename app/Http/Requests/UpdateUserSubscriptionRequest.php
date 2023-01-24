<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Subscription request",
 *      description="Update Subscription request",
 *      type="object",
 *      required={"user_id", "max_accounts"},
 *      @OA\Property(
 *          property="user_id",
 *          title="User Id",
 *          description="User Id",
 *          example="1",
 *          format="integer"
 *      ),
 *      @OA\Property(
 *          property="max_accounts",
 *          title="Max Accounts",
 *          description="Max Accounts",
 *          example="-1",
 *          format="integer"
 *      )
 * )
 */
class UpdateUserSubscriptionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'max_accounts' => 'required'
        ];
    }
}
