<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'expires_at', 'plan_id', 'invoice', 'starts_at', 'status'];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
