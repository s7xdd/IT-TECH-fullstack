<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use URL;

class Product extends Model
{

    protected $fillable = [
        'type',
        'name',
        'sku',
        'slug',
        'published',
        'category_id',
        'brand_id',
        'photos',
        'thumbnail_img',
        'product_type',
        'added_by',
        'user_id',
        'vat',
        'video_provider',
        'video_link',
        'unit_price',
        'variant_product',
        'attributes',
        'choice_options',
        'variations',
        'cash_on_delivery',
        'current_stock',
        'unit',
        'min_qty',
        'low_stock_quantity',
        'discount',
        'discount_type',
        'discount_start_date',
        'discount_end_date',
        'tax',
        'tax_type',
        'shipping_type',
        'shipping_cost',
        'est_shipping_days',
        'num_of_sale',
        'rating',
        'return_refund'
    ];

    protected $with = ['product_translations', 'seo'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function getSeoTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $seo_translations = $this->seo->where('lang', $lang)->first();
        return $seo_translations != null ? $seo_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function seo()
    {
        return $this->hasMany(ProductSeo::class);
    }

    public function image($path)
    {
        return URL::to($path);
    }

    public function tabs()
    {
        return $this->hasMany(ProductTabs::class);
    }

    public function tabsLang($lang = null)
    {
        // Use the default app locale if no language is provided
        $lang = $lang ?? app()->getLocale();

        return $this->hasMany(ProductTabs::class)->where('lang', $lang);
    }

    public function recentlyViewedProducts()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function getMinPriceAttribute()
    {
        return $this->stocks->min('price');
    }

    public function collectionProducts()
    {
        return $this->belongsToMany(CollectionProduct::class, 'collection_product_product');
    }
}
