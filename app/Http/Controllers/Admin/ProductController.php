<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\ProductTabs;
use App\Models\ProductSeo;
use App\Models\User;
use App\Models\ProductAttributes;
use Image;
use Auth;
use Carbon\Carbon;
use Combinations;
// use CoreComponentRepository;
use Artisan;
use Cache;
use Exception;
use Storage;
use Str;
use File;
use Hash;
use DB;

class ProductController extends Controller
{


    public function all_products(Request $request)
    {
        $request->session()->put('last_url', url()->full());
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
        $category = ($request->has('category')) ? $request->category : '';
        
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            if ($col_name == 'status') {
                $products = $products->where('published', $query);
            } else {
                $products = $products->orderBy($col_name, $query);
            }

            $sort_type = $request->type;
        }
        if ($request->has('category') && $request->category !== '0') {
            $childIds = [];
            $categoryfilter = $request->category;
            $childIds[] = array($request->category);
            
            if($categoryfilter != ''){
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

       

        $products = $products->paginate(15);
        $type = 'All';

        return view('backend.products.index', compact('category','products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('is_active',1)->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function get_attribute_values(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('is_active',1)->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslatedName(env('DEFAULT_LANGUAGE', 'en')) . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo '<pre>';
        // // echo env('DEFAULT_LANGUAGE', 'en');
        // print_r($request->all());
        // die;

        $skuMain = $request->input('sku') ?? generateUniqueSKU();
       
        $product = new Product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->user_id = Auth::user()->id;
        $product->vat = $request->vat;
        $product->sku = cleanSKU($skuMain);
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->unit_price = $request->has('price') ? $request->price : 0;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }
       
        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;

        $product->published = 1;
        if ($request->button == 'draft') {
            $product->published = 0;
        }

        if ($request->has('return_refund')) {
            $product->return_refund = 1;
        }

        $product->save();

        $tags = array();
        if (isset($request->tags[0]) && $request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }

        $product_translation                       = ProductTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE', 'en'), 'product_id' => $product->id]);
        $product_translation->name = $request->name;
        $product_translation->tags = implode(',', $tags);
        $product_translation->description = $request->description;
        $product_translation->save();

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->downloadAndResizeImage('main_product',$file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery = $this->downloadAndResizeImage('main_product',$request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }

        $product->save();

        // SEO
        $seo = ProductSeo::firstOrNew(['lang' => env('DEFAULT_LANGUAGE', 'en'), 'product_id' => $product->id]);

        $seo->meta_title        = $request->meta_title;
        $seo->meta_description  = $request->meta_description;

        $keywords = array();
        if (isset($request->meta_keywords[0]) && $request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }
        $seo->meta_keywords         = implode(',', $keywords);
        $seo->og_title              = $request->og_title;
        $seo->og_description        = $request->og_description;
        $seo->twitter_title         = $request->twitter_title;
        $seo->twitter_description   = $request->twitter_description;

        if ($request->meta_title == null) {
            $seo->meta_title        = $product->name;
        }
        if ($request->og_title == null) {
            $seo->og_title          = $product->name;
        }
        if ($request->twitter_title == null) {
            $seo->twitter_title     = $product->name;
        }

        $seo->save();

        // Tabs
        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {
                $p_tab = $product->tabs()->create([
                    'lang'    => env('DEFAULT_LANGUAGE', 'en'),
                    'heading' => $tab['tab_heading'],
                    'content' => $tab['tab_description'],
                ]);
            }
        }

        $product_stock = new ProductStock;
        $product_stock->product_id = $product->id;
        $product_stock->sku = $skuMain;
        $product_stock->qty = $request->current_stock;

        $offertag       = '';
        $productOrgPrice = $request->price;
        $discountPrice = $productOrgPrice;
        $discount_applicable = false;

        if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            if ($product->discount_type == 'percent') {
                $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                $offertag = $product->discount . '% OFF';
            } elseif ($product->discount_type == 'amount') {
                $discountPrice = $productOrgPrice - $product->discount;
                $offertag = 'AED '.$product->discount.' OFF';
            }
        }

        $product_stock->price       = $productOrgPrice;
        $product_stock->offer_price = $discountPrice;
        $product_stock->offer_tag   = $offertag;
        $product_stock->save();

        flash(trans('messages.product').' '.trans('messages.created_msg'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.all');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit(Request $request, $id)
    {
        $lang = $request->lang;
       
        $product = Product::with(['tabs' => function ($query) use ($lang) {
            $query->where('lang', $lang);
        }, 'seo','stocks'])->findOrFail($id);

       
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function downloadAndResizeImage($product_type, $imageUrl, $sku, $mainImage = false, $count = 1, $update = false)
    {
        $data_url = '';

        // try {
            $ext = $imageUrl->getClientOriginalExtension();
            
            if($product_type == 'main_product'){
                $path = 'products/'. $sku . '/main/';
            }else{
                $path = 'products/'. $sku . '/';
            }
            

            if ($mainImage) {
                $filename = $path . $sku . '.' . $ext;
            } else {
                $n = $sku . '_gallery_' .  $count;
                $filename = $path . $n . '.' . $ext;
            }

            
            // Download the image from the given URL
            $imageContents = file_get_contents($imageUrl);

            // Save the original image in the storage folder
            Storage::disk('public')->put($filename, $imageContents);
            $data_url = Storage::url($filename);
           
            // Create an Intervention Image instance for the downloaded image
            $image = Image::make($imageContents);
           
            // Resize and save three additional copies of the image with different sizes
            $sizes = config('app.img_sizes'); // Specify the desired sizes in pixels
           
            foreach ($sizes as $size) {
                $resizedImage = $image->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                if ($mainImage) {
                    $filename2 = $path . $sku . "_{$size}px" . '.' . $ext;
                } else {
                    $n = $sku . '_gallery_' .  $count . "_{$size}px";
                    $filename2 = $path . $n . '.' . $ext;
                }

                // Save the resized image in the storage folder
                Storage::disk('public')->put($filename2, $resizedImage->encode('jpg'));

                // $data_url[] = Storage::url($filename2);
            }
        // } catch (Exception $e) {
        //     echo 'catch';
        //     die;
        // }

        return $data_url;
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $skuMain = $request->input('sku') ?? generateUniqueSKU();

        if ($request->lang == env("DEFAULT_LANGUAGE",'en')) {
            $product->name = $request->name;
        }
        
        $product->category_id       = $request->category_id;
        $product->brand_id          = $request->brand_id;
        $product->user_id           = Auth::user()->id;
        $product->vat               = $request->vat;
        $product->sku               = cleanSKU($skuMain);
        $product->video_provider    = $request->video_provider;
        $product->video_link        = $request->video_link;
        $product->discount          = $request->discount;
        $product->discount_type     = $request->discount_type;
        $product->unit_price        = $request->has('price') ? $request->price : 0;
        $product->published         = ($request->has('published')) ? 1 : 0;

        $tags = array();
        if (isset($request->tags[0]) && $request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->video_provider            = $request->video_provider;
        $product->video_link                = $request->video_link;
        $product->discount                  = $request->discount;
        $product->discount_type             = $request->discount_type;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date   = strtotime($date_var[0]);
            $product->discount_end_date     = strtotime($date_var[1]);
        }

        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count    = Product::where('slug', 'LIKE', $slug . '%')->where('id','!=',$id)->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;

        if ($request->has('return_refund')) {
            $product->return_refund = 1;
        }

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->downloadAndResizeImage('main_product',$file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::disk('public')->exists(str_replace('storage/', '', $product->thumbnail_img))) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::disk('public')->exists(str_replace('storage/', '', $path))) {
                            Storage::disk('public')->delete(str_replace('storage/', '', $path));
                        }
                    }
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->thumbnail_img));
                }
            }
            $gallery = $this->downloadAndResizeImage('main_product',$request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }
        $product->save();

        $product_translation                = ProductTranslation::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $product_translation->name          = $request->name;
        $product_translation->tags          = implode(',', $tags);
        $product_translation->description   = $request->description;
        $product_translation->save();

        $seo                        = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $seo->meta_title            = $request->meta_title;
        $seo->meta_description      = $request->meta_description;

        $keywords = array();
        if (isset($request->meta_keywords[0]) && $request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }
        $seo->meta_keywords         = implode(',', $keywords);
        $seo->og_title              = $request->og_title;
        $seo->og_description        = $request->og_description;
        $seo->twitter_title         = $request->twitter_title;
        $seo->twitter_description   = $request->twitter_description;

        if ($request->meta_title == null) {
            $seo->meta_title        = $product->name;
        }
        if ($request->og_title == null) {
            $seo->og_title          = $product->name;
        }
        if ($request->twitter_title == null) {
            $seo->twitter_title     = $product->name;
        }
        $seo->save();

        if ($request->has('tabs')) {
            ProductTabs::where('lang', $request->lang)->where('product_id', $product->id)->delete();
           foreach ($request->tabs as $tab) {
                $p_tab = $product->tabs()->create([
                    'lang'    => $request->lang,
                    'heading' => $tab['tab_heading'],
                    'content' => $tab['tab_description'],
                ]);
            }
        }
        $product_stock                  = ProductStock::where('product_id', $product->id)->first();
     
        if(!empty($product_stock)){
            $product_stock->product_id      = $product->id;
            $product_stock->sku             = $skuMain;
            $product_stock->qty             = $request->current_stock;

            $price  = 0;
            $offertag       = '';
            $productOrgPrice = $request->price;
            $discountPrice = $productOrgPrice;

            $discount_applicable = false;
            if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                if ($product->discount_type == 'percent') {
                    $discountPrice  = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                    $offertag       = $product->discount . '% OFF';
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice  = $productOrgPrice - $product->discount;
                    $offertag       = 'AED '.$product->discount.' OFF';
                }
            }

            $product_stock->price       = $productOrgPrice;
            $product_stock->offer_price = $discountPrice;
            $product_stock->offer_tag   = $offertag;
            $product_stock->save();
        }
        
        flash(trans('messages.product').' '.trans('messages.updated_msg'))->success();
        
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.all');
    }
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // foreach ($product->product_translations as $key => $product_translations) {
        //     $product_translations->delete();
        // }

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

  
    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

  
    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && $seller->invalid_at != '0000-00-00' && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function delete_thumbnail(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        $fil_url = str_replace('/storage/', '', $product->thumbnail_img);
        $fil_url = $path = Storage::disk('public')->path($fil_url);

        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                // if (Storage::exists($path)) {
                //     Storage::delete($path);
                // }
                unlink($path);
            }

            // Storage::delete($product->thumbnail_img);1
            unlink($fil_url);
            $product->thumbnail_img = null;
            $product->save();
            return 1;
        }
    }

    public function delete_gallery(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);
        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);

            $thumbnail_img = explode(',', $product->photos);
            $thumbnail_img =  array_diff($thumbnail_img, [$request->url]);
            if ($thumbnail_img) {
                $product->photos = implode(',', $thumbnail_img);
            } else {
                $product->photos = null;
            }

            $product->save();
            return 1;
        } else {
            return 0;
        }
    }

}
