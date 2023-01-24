<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Signal Follower request",
 *      type="object",
 *      required={"account_id"},
 *      @OA\Property(
 *          property="account_id",
 *          title="Follower Account id",
 *          type="integer",
 *          description="Follower Account Id",
 *          example="1"
 *      )
 * )
 */
class ManageSignalFollowerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'required'
        ];
    }
}
