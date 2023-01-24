<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *      title="Create User request",
 *      description="Store User request body data",
 *      type="object",
 *      required={"email","password", "name"},
 *      @OA\Property(
 *          property="name",
 *          title="name",
 *          description="User Name",
 *          example="Test User"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          title="email",
 *          format="email",
 *          description="User Email",
 *          example="1@1.com"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          title="password",
 *          format="password",
 *          description="Password",
 *          example="pwd123"
 *      )
 * )
 */
class CreateUserRequest extends FormRequest
{

    public function __construct(Request $request)
    {
        $request->merge([
            'username' => $request->email,
            'password'   => Hash::make($request->password),
            'creator_id' => $request->user()->id,
            'manager_id' => $request->user()->id
        ]);

    }
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:admin_users',
            'password' => 'required|min:6',
            'name' => 'required'
        ];
    }
}
