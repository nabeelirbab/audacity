<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema:'AddUserChallengeRequest')]
class AddUserChallengeRequest extends FormRequest
{

    #[OA\Property(type: 'string')]
    public string $email = '';

    #[OA\Property(type: 'string')]
    public string $name = '';

    #[OA\Property(type: 'integer')]
    public int $plan_id = 0;

    public function __construct(Request $request)
    {
        $request->merge([
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
            'name' => 'required',
            'email' => 'required',
            'plan_id' => 'required'
        ];
    }
}
