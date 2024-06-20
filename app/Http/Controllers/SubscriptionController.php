<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $subscriptions = Subscription::with('plan')->where('user_id', auth()->id())->get();
        return $this->response(true, 'success', $subscriptions);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'id' => 'required|exists:subscription_plans,id'
        ]);
        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->first());
        }
        $plan = Subscription::create([
            'user_id'    => auth()->id(),
            'invoice'    => 'INV-' . time(),
            'plan_id'    => $request->id,
            'starts_at'  => now(),
            'expires_at' => now()->addMonths($request->duration),
            'status'     => 2,
        ]);
        if ($plan) {
            return $this->response(true, 'Subscription created successfully', $plan);
        }
        return $this->response(false, 'Subscription creation failed');
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
