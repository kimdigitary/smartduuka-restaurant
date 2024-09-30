<?php

namespace App\Models;

use App\Enums\Status;
use App\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = "taxes";
    protected $fillable = ['name', 'code', 'tax_rate', 'type', 'status'];
    protected $casts = [
        'id'       => 'integer',
        'name'     => 'string',
        'code'     => 'string',
        'tax_rate' => 'string',
        'type'     => 'integer',
        'status'   => 'integer',
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class)->where(['status' => Status::ACTIVE]);
    }
}