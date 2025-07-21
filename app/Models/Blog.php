<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Blog extends Model
{

  protected $fillable = ['slug', 'name', 'description', 'image', 'status', 'blog_date', 'meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'keywords'];

}
