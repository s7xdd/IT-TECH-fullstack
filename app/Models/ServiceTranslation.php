<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
  protected $fillable = [
    'service_id', 'name', 'description', 'lang', 'meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description'
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }
}
