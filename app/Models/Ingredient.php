<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ingredient extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $table = "ingredients";
    protected $fillable = [
        'name', 'buying_price', 'unit', 'quantity', 'quantity_alert', 'registerMediaConversionsUsingModelInstance'
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id'             => 'integer',
        'name'           => 'string',
        'buying_price'   => 'integer',
        'unit'           => 'integer',
        'quantity'       => 'integer',
        'quantity_alert' => 'integer',
    ];
}
