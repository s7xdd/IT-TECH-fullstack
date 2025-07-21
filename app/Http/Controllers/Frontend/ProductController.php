<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FrontendController;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageTranslations;
use App\Models\PageSeos;
use App\Models\Brand;
use App\Models\HomeSlider;
use App\Models\BusinessSetting;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\WebHomeProductsCollection;
use Storage;
use Validator;
use Mail;
use DB;
use Hash;
use Cache;
use Carbon\Carbon;

class ProductController extends Controller
{
    private $frontController;

    function __construct(FrontendController $frontController)
    {
        $this->frontController = $frontController;
    }

    public function searchSuggestions(Request $request)
    {
        $sort_search = $request->get('search');
        $products = Product::where(function ($query) use ($sort_search) {
            $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                $q->where('sku', 'like', '%' . $sort_search . '%');
            })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
            });
        })->where('published', 1)->limit(5)
            ->get();

        return response()->json($products);
    }


    public function index(Request $request)
    {
        $price = $request->price_range;
        $min_price = $max_price = 0;
        if ($price != null) {
            $range = explode('-', $price);
            $min_price = $range[0];
            $max_price = $range[1];
        }

        $lang = getActiveLanguage();

        $getCategory = $request->query('getCategory') ?? 0;
        $getBrand = $request->query('getBrand') ?? 0;
        $getSEO = $request->query('getSEO') ?? 0;

        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 0;
        $category = $request->has('category') ? $request->category  : false;
        $brand = $request->has('brand') ? $request->brand  : false;
        $occasion = $request->has('occasion') ? $request->occasion  : false;
        $sort_by = $request->has('sort_by') ? $request->sort_by : null;

        // DB::enableQueryLog();
        $product_query  = Product::wherePublished(1);
        $categoryData = null;
        if ($category) {
            $categoryData = Category::whereHas('category_translations', function ($query) use ($category) {
                $query->where('slug', $category);
            })->where('is_active', 1)->first();

            $childIds = [];
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category) {
                $query->where('slug', $category);
            })->where('is_active', 1)->pluck('id')->toArray();

            $childIds[] = $category_ids;
            if (!empty($category_ids)) {
                foreach ($category_ids as $cId) {
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            // print_r($childIds);
            // die;
            $product_query->whereIn('category_id', $childIds);
        }

        if ($brand) {
            $brand_ids = Brand::whereHas('brand_translations', function ($query) use ($brand) {
                $query->where('slug', $brand);
            })->where('is_active', 1)->pluck('id')->toArray();

            $product_query->whereIn('brand_id', $brand_ids);
        }

        if ($sort_by) {
            switch ($sort_by) {
                case 'latest':
                    $product_query->latest();
                    break;
                case 'oldest':
                    $product_query->oldest();
                    break;
                case 'name_asc':
                    $product_query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $product_query->orderBy('name', 'desc');
                    break;
                case 'price_high':
                    $product_query->select('*', DB::raw("
                                        (CASE
                                            WHEN discount > 0
                                                AND (discount_start_date IS NULL OR discount_start_date <= NOW())
                                                AND (discount_end_date IS NULL OR discount_end_date >= NOW())
                                            THEN
                                                CASE
                                                    WHEN discount_type = 'percentage'
                                                        THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                    WHEN discount_type = 'amount'
                                                        THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                    ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                END
                                            ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                        END) as sort_price
                                    "));
                    $product_query->orderBy('sort_price', 'desc');
                    break;
                case 'price_low':
                    $product_query->select('*', DB::raw("
                                            (CASE
                                                WHEN discount > 0
                                                    AND (discount_start_date IS NULL OR discount_start_date <= NOW())
                                                    AND (discount_end_date IS NULL OR discount_end_date >= NOW())
                                                THEN
                                                    CASE
                                                        WHEN discount_type = 'percentage'
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                        WHEN discount_type = 'amount'
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                        ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                    END
                                                ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                            END) as sort_price
                                        "));
                    $product_query->orderBy('sort_price', 'asc');
                    break;
                default:
                    # code...
                    break;
            }
        }

        if ($request->search) {
            $sort_search = $request->search;
            $products = $product_query->where(function ($query) use ($sort_search) {
                $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                    $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
                });
            });
            // SearchUtility::store($sort_search, $request);
        }

        if ($max_price != 0 && $min_price != 0) {
            $product_query->whereHas('stocks', function ($query) use ($min_price, $max_price) {
                $query->whereBetween('price', [$min_price, $max_price]);
            });
        }

        if ($request->has('offers')) {
            $today = Carbon::now()->timestamp;
            $product_query->where('discount_start_date', '<=', $today) // Offer starts on or before today
                ->where('discount_end_date', '>=', $today);
        }
        $products = $product_query->paginate(10)->appends($request->query());
        // $products = $product_query->get();
        // dd(DB::getQueryLog());

        if ($getCategory === 1) {
            $categories = Cache::rememberForever('categories', function () {
                $details = Category::where('parent_id', 0)->where('is_active', 1)->orderBy('name', 'asc')->get();
                return $details;
            });
        }

        if ($getBrand) {
            $brands = Cache::rememberForever('brands', function () {
                $details = Brand::where('is_active', 1)->orderBy('name', 'asc')->get();
                return $details;
            });
        }

        $page = Page::where('type', 'product_list')->first();

        if ($getSEO) {
            $seo = [
                'title'                 => $page->getTranslation('title', $lang),
                'meta_title'            => $page->getTranslation('meta_title', $lang),
                'meta_description'      => $page->getTranslation('meta_description', $lang),
                'keywords'              => $page->getTranslation('keywords', $lang),
                'og_title'              => $page->getTranslation('og_title', $lang),
                'og_description'        => $page->getTranslation('og_description', $lang),
                'twitter_title'         => $page->getTranslation('twitter_title', $lang),
                'twitter_description'   => $page->getTranslation('twitter_description', $lang),
            ];
        }

        $this->frontController->loadSEO($seo);
        return view('pages.products-listing', compact('page', 'limit', 'products', 'offset', 'min_price', 'max_price', 'category', 'brand', 'occasion', 'sort_by', 'lang', 'categories', 'brands', 'categoryData', 'price'));
    }

    public function loadMoreProducts(Request $request)
    {
        if ($request->ajax()) {
            $lang = getActiveLanguage();

            $price = $request->price_range;
            $min_price = $max_price = 0;
            if ($price != null) {
                $range = explode('-', $price);
                $min_price = $range[0];
                $max_price = $range[1];
            }

            $limit = $request->has('limit') ? $request->limit : 10;
            $offset = $request->has('offset') ? $request->offset : 0;
            $category = $request->has('category') ? $request->category  : false;
            $brand = $request->has('brand') ? $request->brand  : false;
            $sort_by = $request->has('sort_by') ? $request->sort_by : null;

            $product_query  = Product::wherePublished(1);
            $categoryData = null;
            if ($category) {
                $categoryData = Category::whereHas('category_translations', function ($query) use ($category) {
                    $query->where('slug', $category);
                })->where('is_active', 1)->first();

                $childIds = [];
                $category_ids = Category::whereHas('category_translations', function ($query) use ($category) {
                    $query->where('slug', $category);
                })->where('is_active', 1)->pluck('id')->toArray();

                $childIds[] = $category_ids;
                if (!empty($category_ids)) {
                    foreach ($category_ids as $cId) {
                        $childIds[] = getChildCategoryIds($cId);
                    }
                }

                if (!empty($childIds)) {
                    $childIds = array_merge(...$childIds);
                    $childIds = array_unique($childIds);
                }
                // print_r($childIds);
                // die;
                $product_query->whereIn('category_id', $childIds);
            }

            if ($brand) {
                $brand_ids = Brand::whereHas('brand_translations', function ($query) use ($brand) {
                    $query->where('slug', $brand);
                })->where('is_active', 1)->pluck('id')->toArray();

                $product_query->whereIn('brand_id', $brand_ids);
            }

            if ($sort_by) {
                switch ($sort_by) {
                    case 'latest':
                        $product_query->latest();
                        break;
                    case 'oldest':
                        $product_query->oldest();
                        break;
                    case 'name_asc':
                        $product_query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $product_query->orderBy('name', 'desc');
                        break;
                    case 'price_high':
                        $product_query->select('*', DB::raw("
                                            (CASE
                                                WHEN discount > 0
                                                    AND (discount_start_date IS NULL OR discount_start_date <= NOW())
                                                    AND (discount_end_date IS NULL OR discount_end_date >= NOW())
                                                THEN
                                                    CASE
                                                        WHEN discount_type = 'percentage'
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                        WHEN discount_type = 'amount'
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                        ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                    END
                                                ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                            END) as sort_price
                                        "));
                        $product_query->orderBy('sort_price', 'desc');
                        break;
                    case 'price_low':
                        $product_query->select('*', DB::raw("
                                                (CASE
                                                    WHEN discount > 0
                                                        AND (discount_start_date IS NULL OR discount_start_date <= NOW())
                                                        AND (discount_end_date IS NULL OR discount_end_date >= NOW())
                                                    THEN
                                                        CASE
                                                            WHEN discount_type = 'percentage'
                                                                THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                            WHEN discount_type = 'amount'
                                                                THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                            ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                        END
                                                    ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                END) as sort_price
                                            "));
                        $product_query->orderBy('sort_price', 'asc');
                        break;
                    default:
                        # code...
                        break;
                }
            }

            if ($request->search) {
                $sort_search = $request->search;
                $products = $product_query->where(function ($query) use ($sort_search) {
                    $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                        $q->where('sku', 'like', '%' . $sort_search . '%');
                    })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                        $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
                    });
                });
                // SearchUtility::store($sort_search, $request);
            }

            if ($max_price != 0 && $min_price != 0) {
                $product_query->whereHas('stocks', function ($query) use ($min_price, $max_price) {
                    $query->whereBetween('price', [$min_price, $max_price]);
                });
            }

            $products = $product_query->paginate(10)->appends($request->query());

            // Check if services exist and render the partial view
            if ($products->isEmpty()) {
                return response()->json(['html' => '', 'hasMore' => false]);
            }

            // Render the partial view and return it with a flag indicating if more pages are available
            $html = view('pages.product_card', ['products' => $products, 'lang' => $lang])->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $products->hasMorePages(),
            ]);
        }

        // Return a fallback if the request is not via AJAX
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function productDetails(Request $request, $slug)
    {

        $lang = getActiveLanguage();

        $product = '';
        $response = $relatedProducts = [];
        if ($slug !=  '') {
            $product = Product::with(['stocks'])->where('published', 1)->where('slug', $slug)->first();

            $category = [
                'id' => 0,
                'name' => "",
                'slug' => "",
                'logo' => "",
            ];

            if ($product) {

                trackRecentlyViewed($product->id);

                if ($product->category != null) {
                    $category = [
                        'id' => $product->category->id ?? '',
                        'name' => $product->category->getTranslation('name', $lang) ?? '',
                        'slug' => $product->category->getTranslation('slug', $lang) ?? '',
                        'logo' => uploaded_asset($product->category->getTranslation('icon', $lang) ?? ''),
                    ];
                }

                $photo_paths = explode(',', $product->photos);

                $photos = [];
                if (!empty($photo_paths)) {
                    foreach ($photo_paths as $php) {
                        $photos[] = get_product_image($php);
                    }
                }
                $priceData = getProductOfferPrice($product);

                $response = [
                    'id' => (int)$product->id,
                    'wishlisted' => isWishlisted($product->id),
                    'name' => $product->getTranslation('name', $lang),
                    'slug' => $product->slug,
                    'product_type' => $product->product_type,
                    'brand' => $product->brand->getTranslation('name', $lang) ?? '',
                    'category' => $category,
                    'video_provider' => $product->video_provider ?? '',
                    'video_link' => $product->video_link != null ?  $product->video_link : "",
                    'return_refund' =>  $product->return_refund,
                    'published' =>  $product->published,
                    'photos' => $photos,
                    'thumbnail_image' => get_product_image($product->thumbnail_img),
                    'tags' => explode(',', $product->getTranslation('tags', $lang)),
                    'sku' =>  $product->sku,
                    'quantity' => $product->stocks[0]->qty ?? 0,
                    'description' => $product->getTranslation('description', $lang),
                    'stroked_price' => $priceData['original_price'] ?? 0,
                    'main_price' => $priceData['discounted_price'] ?? 0,
                    'offer_tag' =>  $priceData['offer_tag'],
                    'current_stock' => (int)$product->stocks[0]->qty,
                    'rating' => (float)$product->rating,
                    'rating_count' => (int)Review::where(['product_id' => $product->id])->count(),
                    'tabs' => $product->tabsLang,
                    'meta_title' => $product->getSeoTranslation('meta_title', $lang) ?? '',
                    'meta_description' => $product->getSeoTranslation('meta_description', $lang) ?? '',
                    'meta_keywords' => $product->getSeoTranslation('meta_keywords', $lang) ?? '',
                    'og_title' => $product->getSeoTranslation('og_title', $lang) ?? '',
                    'og_description' => $product->getSeoTranslation('og_description', $lang) ?? '',
                    'twitter_title' => $product->getSeoTranslation('twitter_title', $lang) ?? '',
                    'twitter_description' => $product->getSeoTranslation('twitter_description', $lang) ?? '',
                ];

                $relatedProducts = $this->relatedProducts(4, 0, $slug, $product->category->getTranslation('slug', $lang) ?? '');
            }
        }
        // echo '<pre>';
        // print_r($relatedProducts);
        // die;

        $recentlyViewedProducts = getRecentlyViewedProducts();

        return view('pages.product-details', compact('lang', 'response', 'product', 'relatedProducts', 'recentlyViewedProducts'));
    }


    public function relatedProducts($limit, $offset, $product_slug, $category_slug)
    {

        $product_query = Product::with(['stocks'])->where('published', 1); // Prevent duplication

        if ($category_slug) {
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category_slug) {
                $query->where('slug', $category_slug);
            })->pluck('id')->toArray();

            $childIds[] = $category_ids;
            if (!empty($category_ids)) {
                foreach ($category_ids as $cId) {
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $product_query->whereIn('category_id', $category_ids);
        }
        $product_query->where('slug', '!=', $product_slug)->latest();

        $products = $product_query->skip($offset)->take($limit)->get();

        return $products;
    }
}
