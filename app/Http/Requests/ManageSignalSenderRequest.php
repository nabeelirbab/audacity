<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Signal Sender request",
 *      type="object",
 *      required={"account_id"},
 *      @OA\Property(
 *          property="account_id",
 *          title="Sender Account id",
 *          type="integer",
 *          description="Sender Account Id",
 *          example="1"
 *      )
 * )
 */
class ManageSignalSenderRequest extends FormRequest
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
