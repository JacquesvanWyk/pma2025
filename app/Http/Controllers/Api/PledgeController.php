<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PledgeProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PledgeController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_amount' => 'required|numeric|min:0',
            'month' => 'nullable|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'goal_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'current_amount' => $request->current_amount,
        ];

        if ($request->has('goal_amount')) {
            $data['goal_amount'] = $request->goal_amount;
        }

        // Update existing record for the month, or create if doesn't exist
        $pledge = PledgeProgress::updateOrCreate(
            ['month' => $request->month ?? 'January'],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Pledge progress updated successfully',
            'data' => [
                'current_amount' => $pledge->current_amount,
                'month' => $pledge->month,
                'goal_amount' => $pledge->goal_amount,
                'percentage' => $pledge->percentage,
            ],
        ], 200);
    }
}
