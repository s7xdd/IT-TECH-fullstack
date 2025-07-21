<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CollectionProduct;
use App\Models\Contacts;
use App\Models\HomeSlider;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\Testimonials;
use Efectn\Menu\Facades\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    public function home()
    {
        $data['slider'] = HomeSlider::where('status', 1)->orderBy('sort_order', 'asc')->get();
        $data['slider']->transform(function ($slider) {
            $slider->image = uploaded_asset($slider->image);
            $slider->mobile_image = uploaded_asset($slider->mobile_image);
            return $slider;
        });

        $data['home_categories'] = Cache::rememberForever('home_categories', function () {
            $categories = get_setting('home_categories');
            if ($categories) {
                $details = Category::whereIn('id', json_decode($categories))->where('is_active', 1)
                    ->get();
                return $details;
            }
        });

        $data['home_products'] = Cache::remember('home_products', 3600, function () {
            $product_ids = get_setting('home_products');
            if ($product_ids) {
                $products =  Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
                return $products;
            }
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function collectionProduct(Request $request)
    {
        $pages = $request->query('page');
        $pageReferences = $request->query('page_reference');

        if (!$pages || !$pageReferences) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required query parameters: page and page_reference.',
            ], 400);
        }

        $pageArray = explode(',', $pages);
        $referenceArray = explode(',', $pageReferences);

        $collections = CollectionProduct::with('products')
            ->whereIn('page', $pageArray)
            ->whereIn('page_reference', $referenceArray)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $collections,
        ]);
    }

    public function page($page)
    {
        $page = Page::where('type', $page)->first();
        $lang = getActiveLanguage();

        $pageContent = [
            'title' => $page->getTranslation('title', $lang),
            'content' => $page->getTranslation('content', $lang),
            'sub_title' => $page->getTranslation('sub_title', $lang),
            'heading1' => $page->getTranslation('heading1', $lang),
            'content1' => $page->getTranslation('content1', $lang),
            'heading2' => $page->getTranslation('heading2', $lang),
            'image1' => $page->getTranslation('image1', $lang),
            'content2' => $page->getTranslation('content2', $lang),
            'heading3' => $page->getTranslation('heading3', $lang),
            'content3' => $page->getTranslation('content3', $lang),
            'content4' => $page->getTranslation('content4', $lang),
            'content5' => $page->getTranslation('content5', $lang),
            'heading4' => $page->getTranslation('heading4', $lang),
            'heading5' => $page->getTranslation('heading5', $lang),
            'heading6' => $page->getTranslation('heading6', $lang),
            'heading7' => $page->getTranslation('heading7', $lang),
            'heading8' => $page->getTranslation('heading8', $lang),
            'heading9' => $page->getTranslation('heading9', $lang),
        ];

        return response()->json([
            'status' => true,
            'data' => $pageContent
        ]);
    }

    public function pageSEO()
    {
        $page = Page::where('type', 'contact_us')->first();
        $lang = getActiveLanguage();

        $seo = [
            'title'                 => $page->getTranslation('meta_title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];

        return response()->json([
            'status' => true,
            'data' => $seo
        ]);
    }

    public function blogs()
    {
        $page = Page::where('type', 'blogs')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('meta_title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];

        $blogs =  Blog::where('status', 1)->orderBy('blog_date', 'DESC')->paginate(6);

        return response()->json([
            'status' => true,
            'data' => $blogs,
            'seo' => $seo,
        ]);
    }

    public function submitContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9\-\+\s\(\)]{10,15}$/',
            'subject' => 'required|string|min:5|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $con                = new Contacts();
        $con->name          = $request->name;
        $con->email         = $request->email;
        $con->phone         = $request->phone;
        $con->subject       = $request->subject;
        $con->message       = $request->message;
        $con->save();

        // Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return response()->json([
            'status' => true,
            'message' => 'Your message has been sent successfully!'
        ]);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newsletter_email' => 'required|email|unique:subscribers,email',
        ], [
            'newsletter_email.required' => trans('messages.enter_email'),
            'newsletter_email.email' => trans('messages.enter_valid_email'),
            'newsletter_email.unique' => trans('messages.email_already_subscribed'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }


        Subscriber::create(['email' => $request->newsletter_email]);

        return response()->json(['success' => trans('messages.newsletter_success')]);
    }

    public function services()
    {
        $page = Page::where('type', 'service_list')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('meta_title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];

        $services =  Service::where('status', 1)->orderBy('sort_order', 'ASC')->paginate(6);

        return response()->json([
            'status' => true,
            'data' => $services,
            'seo' => $seo,
        ]);
    }

    public function menu(Request $request)
    {
        $response = [
            'status' => true,
            'data' => [],
        ];

        if ($request->has('blockReference')) {
            $blockReferences = explode(',', $request->blockReference);
            foreach ($blockReferences as $blockReference) {
                $menu = Menu::getByName($blockReference);
                if ($menu) {
                    $response['data'][$blockReference] = $menu;
                }
            }
        }

        if (empty($response['data'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid menu request.',
            ], 400);
        }

        return response()->json($response);
    }
}
