<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Manage User Subscription request",
 *      description="Manage Subscription request",
 *      type="object",
 *      required={"user_id", "signal_id"},
 *      @OA\Property(
 *          property="user_id",
 *          title="User Id",
 *          description="User Id",
 *          example="1",
 *          format="integer"
 *      ),
 *      @OA\Property(
 *          property="signal_id",
 *          title="Signal ID",
 *          description="Signal ID",
 *          example="25",
 *          format="integer"
 *      )
 * )
 */
class ManageUserSubscriptionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'signal_id' => 'required',
            'user_id' => 'required'
        ];
    }
}
