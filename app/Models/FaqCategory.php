<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','is_active','sort_order'];

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function faq_list()
    {
        return $this->hasMany(Faq::class)->where('is_active',1)->orderBy('sort_order', 'ASC' );
    }
}
