<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageTranslations;
use App\Models\PageSeos;
use App\Models\Brand;
use App\Models\HomeSlider;
use App\Models\Service;
use App\Models\BusinessSetting;
use App\Models\Subscriber;
use App\Models\Contacts;
use App\Models\Testimonials;
use App\Models\FaqCategory;
use App\Models\Blog;
use App\Mail\ContactEnquiry;
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
use App\Models\Partners;
use App\Models\Tutorial;
use Storage;
use Validator;
use Mail;
use DB;
use Hash;
use Cache;
use Efectn\Menu\Models\Menus;

class FrontendController extends Controller
{

    public function loadSEO($model)
    {
        SEOTools::setTitle($model['title']);
        OpenGraph::setTitle($model['title']);
        TwitterCard::setTitle($model['title']);

        SEOMeta::setTitle($model['meta_title'] ?? $model['title']);
        SEOMeta::setDescription($model['meta_description']);
        SEOMeta::addKeyword($model['keywords']);

        OpenGraph::setTitle($model['og_title']);
        OpenGraph::setDescription($model['og_description']);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
        OpenGraph::addProperty('type', $model['og_type'] ?? 'website');
        OpenGraph::addImage($model['og_image'] ?? URL::to(asset('assets/img/logo.png')));

        JsonLd::setTitle($model['meta_title']);
        JsonLd::setDescription($model['meta_description']);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model['twitter_title']);
        TwitterCard::setSite("@homeiq");
        TwitterCard::setDescription($model['twitter_description']);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.ico')));
    }

    public function loadDynamicSEO($model)
    {
        SEOTools::setTitle($model->title);
        OpenGraph::setTitle($model->title);
        TwitterCard::setTitle($model->title);

        SEOMeta::setTitle($model->seo_title ?? $model->title);
        SEOMeta::setDescription($model->seo_description);
        SEOMeta::addKeyword($model->keywords);

        OpenGraph::setTitle($model->og_title);
        OpenGraph::setDescription($model->og_description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');

        JsonLd::setTitle($model->seo_title);
        JsonLd::setDescription($model->seo_description);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model->twitter_title);
        TwitterCard::setSite("@homeiq");
        TwitterCard::setDescription($model->twitter_description);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.ico')));
    }

    public function home()
    {
        setGuestToken();
        $page = Page::where('type', 'home')->first();
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

        $this->loadSEO($seo);
        $data['slider'] = HomeSlider::where('status', 1)->orderBy('sort_order', 'asc')->get();

        $data['home_categories'] = Category::where('is_active', 1)
            ->whereIn('id', json_decode(get_setting('home_categories') ?? '[]'))
            ->get();

        $data['home_products'] = Product::where('published', 1)
            ->whereIn('id', json_decode(get_setting('home_products') ?? '[]'))
            ->with('brand')
            ->get();

        $data['home_services'] = Service::where('status', 1)
            ->whereIn('id', json_decode(get_setting('home_services') ?? '[]'))
            ->orderBy('sort_order', 'asc')
            ->get();

        $data['partners'] = Partners::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        $data['testimonials'] = Testimonials::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        $data['blogs'] = Blog::where('status', 1)
            ->orderBy('blog_date', 'desc')
            ->limit(5)
            ->get();

        $data['tutorials'] = Tutorial::where('status', 1)
            ->orderBy('tutorial_date', 'desc')
            ->limit(5)
            ->get();

        // return view('frontend.home',compact('page','data','lang'));

        // return response()->json([
        //     'slider' => $data['slider'],
        //     'products' => $data['home_products'],
        //     'services' => $data['home_services'],
        //     'categories' => $data['home_categories'],
        //     'partners' => $data['partners'],
        //     'testimonials' => $data['testimonials'],
        //     'blogs' => $data['blogs'],
        //     'tutorials' => $data['tutorials'],
        //     'page' => $page,
        //     'lang' => $lang
        // ]);


        //    $menu = Menus::where('name','bottom footer')->with('items')->first();

        //     return response()->json([
        //         'menu' => $menu
        //     ]);

        return view('pages.home', [
            'slider' => $data['slider'],
            'products' => $data['home_products'],
            'services' => $data['home_services'],
            'categories' => $data['home_categories'],
            'partners' => $data['partners'],
            'testimonials' => $data['testimonials'],
            'blogs' => $data['blogs'],
            'tutorials' => $data['tutorials'],
            'page' => $page,
            'lang' => $lang
        ]);
    }

    public function about()
    {
        $page = Page::where('type', 'about_us')->first();
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

        $home_data = Page::where('type', 'home')->first();

        $this->loadSEO($seo);
        return view('pages.about-us', compact('page', 'lang', 'home_data'));
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

        $this->loadSEO($seo);
        $services =  Service::where('status', 1)->orderBy('sort_order', 'ASC')->paginate(6);

        return view('pages.services', compact('page', 'lang', 'services'));
    }

    public function loadMoreService(Request $request)
    {
        if ($request->ajax()) {
            // Get paginated results for the next page
            $services = Service::where('status', 1)
                ->orderBy('sort_order', 'ASC')
                ->paginate(6, ['*'], 'page', $request->page);

            // Check if services exist and render the partial view
            if ($services->isEmpty()) {
                return response()->json(['html' => '', 'hasMore' => false]);
            }

            // Render the partial view and return it with a flag indicating if more pages are available
            $html = view('pages.service_card', compact('services'))->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $services->hasMorePages(),
            ]);
        }

        // Return a fallback if the request is not via AJAX
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function showService($slug)
    {
        $lang = getActiveLanguage();
        $services =  Service::where('status', 1)->where('slug', $slug)->first();
        $latestServices = Service::where('status', 1)->where('slug', '!=', $slug)->orderBy('sort_order', 'ASC')->limit(3)->get();
        $faq_categories = FaqCategory::with(['faq_list'])->where('slug', 'services')->first()->orderBy('sort_order', 'asc')->get();

        return view('pages.service-details', ['service' => $services, 'lang' => $lang, 'faq_categories' => $faq_categories, 'latestServices' => $latestServices]);
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

        $this->loadSEO($seo);
        $blogs =  Blog::where('status', 1)->orderBy('blog_date', 'DESC')->paginate(6);

        return view('pages.blog', compact('page', 'lang', 'blogs'));
    }

    public function tutorials()
    {
        $page = Page::where('type', 'tutorials')->first();
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

        $this->loadSEO($seo);
        $tutorials =  Tutorial::where('status', 1)->orderBy('tutorial_date', 'DESC')->paginate(6);

        return view('pages.tutorial', compact('page', 'lang', 'tutorials'));
    }

    public function loadMoreBlogs(Request $request)
    {
        if ($request->ajax()) {
            $blogs = Blog::where('status', 1)
                ->orderBy('blog_date', 'DESC')
                ->paginate(6, ['*'], 'page', $request->page);

            if ($blogs->isEmpty()) {
                return response()->json(['html' => '', 'hasMore' => false]);
            }

            $html = view('pages.blog_card', compact('blogs'))->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $blogs->hasMorePages(),
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function loadMoreTutorials(Request $request)
    {
        if ($request->ajax()) {
            $tutorials = Tutorial::where('status', 1)
                ->orderBy('tutorial_date', 'DESC')
                ->paginate(6, ['*'], 'page', $request->page);

            if ($tutorials->isEmpty()) {
                return response()->json(['html' => '', 'hasMore' => false]);
            }

            $html = view('pages.tutorial_card', compact('tutorials'))->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $tutorials->hasMorePages(),
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function showBlog($slug)
    {
        $lang = getActiveLanguage();
        $blogs =  Blog::where('status', 1)->where('slug', $slug)->first();

        $seo = [
            'title'                 => $blogs->name,
            'meta_title'            => $blogs->meta_title,
            'meta_description'      => $blogs->meta_description,
            'keywords'              => $blogs->keywords,
            'og_title'              => $blogs->og_title,
            'og_description'        => $blogs->og_description,
            'og_type'               => 'article',
            'og_image'              => uploaded_asset(($blogs->image)),
            'twitter_title'         => $blogs->twitter_title,
            'twitter_description'   => $blogs->twitter_description,
        ];

        $this->loadSEO($seo);
        $recentBlogs = Blog::where('id', '!=', $blogs->id)->where('status', 1)
            ->orderBy('blog_date', 'desc')
            ->take(3)
            ->get();
        $previous = Blog::where('id', '<', $blogs->id)->where('status', 1)
            ->orderBy('blog_date', 'desc')
            ->first();

        $next = Blog::where('id', '>', $blogs->id)->where('status', 1)
            ->orderBy('blog_date', 'asc')
            ->first();
        return view('pages.blog-details', ['blog' => $blogs, 'lang' => $lang, 'recentBlogs' => $recentBlogs, 'previousBlog' => $previous, 'nextBlog' => $next]);
    }

    public function showTutorial($slug)
    {
        $lang = getActiveLanguage();
        $tutorials =  Tutorial::where('status', 1)->where('slug', $slug)->first();

        $seo = [
            'title'                 => $tutorials->name,
            'meta_title'            => $tutorials->meta_title,
            'meta_description'      => $tutorials->meta_description,
            'keywords'              => $tutorials->keywords,
            'og_title'              => $tutorials->og_title,
            'og_description'        => $tutorials->og_description,
            'og_type'               => 'article',
            'og_image'              => uploaded_asset(($tutorials->image)),
            'twitter_title'         => $tutorials->twitter_title,
            'twitter_description'   => $tutorials->twitter_description,
        ];

        $this->loadSEO($seo);
        $recentTutorials = Tutorial::where('id', '!=', $tutorials->id)->where('status', 1)
            ->orderBy('tutorial_date', 'desc')
            ->take(2)
            ->get();
        $previous = Tutorial::where('id', '<', $tutorials->id)->where('status', 1)
            ->orderBy('tutorial_date', 'desc')
            ->first();

        $next = Tutorial::where('id', '>', $tutorials->id)->where('status', 1)
            ->orderBy('tutorial_date', 'asc')
            ->first();
        return view('pages.tutorial-details', ['tutorial' => $tutorials, 'lang' => $lang, 'recentTutorials' => $recentTutorials, 'previousTutorial' => $previous, 'nextTutorial' => $next]);
    }

    public function terms()
    {
        $page = Page::where('type', 'terms')->first();
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

        $this->loadSEO($seo);
        return view('pages.terms-conditions', compact('page', 'lang'));
    }

    public function privacy()
    {
        $page = Page::where('type', 'privacy_policy')->first();
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

        $this->loadSEO($seo);
        return view('pages.privacy-policy', compact('page', 'lang'));
    }

    public function returnPolicy()
    {
        $page = Page::where('type', 'return_policy')->first();
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

        $this->loadSEO($seo);
        return view('pages.return-policy', compact('page', 'lang'));
    }


    public function contact()
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

        $this->loadSEO($seo);
        return view('pages.contact', compact('page', 'lang'));
    }

    public function submitContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|regex:/^[0-9\-\+\s\(\)]{10,15}$/',
            'subject' => 'nullable|string|min:5|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $con                = new Contacts;
        $con->name          = $request->name;
        $con->email         = $request->email;
        $con->phone         = $request->phone;
        $con->subject       = $request->subject;
        $con->message       = $request->message;
        $con->save();

        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'newsletter_email' => 'required|email|unique:subscribers,email',
        ], [
            'newsletter_email.required' => trans('messages.enter_email'),
            'newsletter_email.email' => trans('messages.enter_valid_email'),
            'newsletter_email.unique' => trans('messages.email_already_subscribed'),
        ]);

        Subscriber::create(['email' => $request->newsletter_email]);

        return response()->json(['success' => trans('messages.newsletter_success')]);
    }

    public function brands()
    {
        $page = Page::where('type', 'brands_list')->first();
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

        $this->loadSEO($seo);

        $brands = Brand::where('is_active', 1)->orderBy('name', 'ASC')->get();

        return view('pages.brand-listing', compact('page', 'lang', 'brands'));
    }

    public function faq()
    {
        $page = Page::where('type', 'faq')->first();
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

        $this->loadSEO($seo);

        $faq_categories = FaqCategory::with(['faq_list'])->where('is_active', 1)->orderBy('sort_order', 'asc')->get();
        // echo '<pre>';
        // print_r($faq_categories);
        // die;
        return view('pages.faq', compact('page', 'lang', 'faq_categories'));
    }
}
