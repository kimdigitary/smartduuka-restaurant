<?php

namespace App\Models;

use App\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ThemeSetting extends TenantModel implements HasMedia
{
    use InteractsWithMedia;
    protected $table = "settings";

    protected $fillable = [
        'tenant_id',
        'key',
        'group',
        'payload'
    ];

    public function getLogoAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('theme-logo'))) {
            return asset($this->getFirstMediaUrl('theme-logo'));
        }
        return asset('images/theme/theme-logo.png');
    }

    public function getFaviconLogoAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('theme-favicon-logo'))) {
            return asset($this->getFirstMediaUrl('theme-favicon-logo'));
        }
        return asset('images/theme/theme-favicon-logo.png');
    }

    public function getFooterLogoAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('theme-footer-logo'))) {
            return asset($this->getFirstMediaUrl('theme-footer-logo'));
        }
        return asset('images/theme/theme-footer-logo.png');
    }
}