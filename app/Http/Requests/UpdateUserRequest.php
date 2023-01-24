<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *      title="Update User request",
 *      description="Store Signal request body data",
 *      type="object",
 *      @OA\Property(
 *          property="name",
 *          title="name",
 *          description="User Name",
 *          example="Test User"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          title="password",
 *          format="password",
 *          description="User Password",
 *          example="pwd123"
 *      )
 * )
 */
class UpdateUserRequest extends FormRequest
{

    public function __construct(Request $request)
    {
        $request->merge([
            'password'   => Hash::make($request->password),
        ]);

    }
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => 'min:6',
            'name' => ''
        ];
    }
}
