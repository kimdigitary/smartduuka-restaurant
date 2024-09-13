<?php
namespace App\Models;
use App\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ingredient extends TenantModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $table = "ingredients";
    protected $fillable = [
        'name', 'buying_price', 'unit', 'quantity', 'quantity_alert', 'registerMediaConversionsUsingModelInstance','status'
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id'             => 'integer',
        'name'           => 'string',
//        'buying_price'   => 'integer',
        'unit'           => 'string',
//        'quantity'       => 'integer',
//        'quantity_alert' => 'integer',
    ];
}
