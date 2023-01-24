<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema:'DisableUserChallengeRequest')]
class DisableUserChallengeRequest extends FormRequest
{

    #[OA\Property(type: 'string')]
    public string $email = '';

    #[OA\Property(type: 'string')]
    public string $ids = '';

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
            'email' => 'required',
            'ids' => 'required'
        ];
    }
}
