<?php

namespace App\Http\Requests;

use App\Enums\CopierRiskType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *      title="Create Signal request",
 *      description="Store Signal request body data",
 *      type="object",
 *      required={"title", "risk_type"},
 *      @OA\Property(
 *          property="title",
 *          title="title",
 *          description="Signal Title",
 *          example="Test Signal"
 *      ),
 *      @OA\Property(
 *          property="risk_type",
 *          title="Risk Type",
 *          description="Risk Type",
 *          format="string",
 *          enum={"MULTIPLIER", "FIXED_LOT", "MONEY_RATIO", "RISK_PERCENT", "SCALING"},
 *          example="MULTIPLIER"
 * )
 * )
*/
class CreateSignalRequest extends FormRequest
{

    public function __construct(Request $request)
    {
        $request->merge([
            'risk_type' => CopierRiskType::fromCase($request->risk_type),
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
            'title' => 'required',
            'risk_type' => 'required'
        ];
    }
}
