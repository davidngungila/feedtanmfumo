<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\SocialWelfare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelfareController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $welfare = SocialWelfare::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($welfare) {
                return [
                    'id' => $welfare->id,
                    'welfare_number' => $welfare->welfare_number,
                    'type' => $welfare->type,
                    'benefit_type' => $welfare->benefit_type,
                    'amount' => (float) $welfare->amount,
                    'status' => $welfare->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $welfare,
        ]);
    }

    public function show(SocialWelfare $welfare)
    {
        if ($welfare->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $welfare->id,
                'welfare_number' => $welfare->welfare_number,
                'type' => $welfare->type,
                'benefit_type' => $welfare->benefit_type,
                'amount' => (float) $welfare->amount,
                'status' => $welfare->status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        // TODO: Implement welfare request
        return response()->json([
            'success' => true,
            'message' => 'Welfare request submitted successfully',
        ], 201);
    }
}
