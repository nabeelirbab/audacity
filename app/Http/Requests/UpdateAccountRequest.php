<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update User request",
 *      description="Store Account request body data",
 *      type="object",
 *      @OA\Property(
 *          property="title",
 *          title="title",
 *          format="string",
 *          description="Account Title",
 *          example="Test Account"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          title="password",
 *          format="string",
 *          description="Account Password",
 *          example="pwd123"
 *      ),
 * )
 */
class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => '',
            'password' => ''
        ];
    }
}
