<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderState;
use App\Events\OrderStateChanged;
use App\Http\Controllers\Controller;
use App\Repositories\MT4OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function processOpen(Request $request)
    {
        try {
            event(new OrderStateChanged(new MT4OrderRepository($request->all()), OrderState::OPENED));
        } catch (\Exception $e) {
            //$this->updateApiEvent('callback', $e->getMessage());
            //throw $e;
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Processed: '.json_encode($request->all())]);
    }

    public function processClose(Request $request)
    {
        try {
            event(new OrderStateChanged(new MT4OrderRepository($request->all()), OrderState::CLOSED));
        } catch (\Exception $e) {
            //$this->updateApiEvent('callback', $e->getMessage());
            //throw $e;
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Processed: '.json_encode($request->all())]);
    }
}
