<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Service extends Model
{

    protected $with = ['service_translations'];

    protected $fillable = ['name', 'sort_order', 'slug', 'image', 'custom_fields', 'icon', 'status', 'parent_id'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $service_translation = $this->service_translations->where('lang', $lang)->first();
        return $service_translation != null ? $service_translation->$field : $this->$field;
    }

    public function service_translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }
}
