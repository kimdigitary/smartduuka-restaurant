<?php

namespace App\Models;

use App\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tenant extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'logo',
        'tagline',
        'status',
        'website',
        'address',
        'username'
    ];

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('tenant'))) {
            return asset($this->getFirstMediaUrl('tenant'));
        }
        return asset('images/tenant/english.png');
    }

}