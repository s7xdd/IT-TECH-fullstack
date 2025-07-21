<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use DB;

class BlogController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_blogs', ['only' => ['index','create','store','edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('blog_date', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $blogs = $blogs->where('name', 'like', '%' . $sort_search . '%');
        }
        $blogs = $blogs->paginate(15);
        return view('backend.blogs.index', compact('blogs', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'slug' => 'required',
        ]);
       
        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = Blog::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $blog                       = new Blog;
        $blog->name                 = $request->name ?? NULL;
        $blog->slug                 = $slug;
        $blog->image                = $request->image;
        $blog->description          = $request->description ?? NULL;
        $blog->blog_date            = $request->blog_date ?? date('Y-m-d');
        $blog->status               = $request->status;
        $blog->meta_title           = $request->meta_title;
        $blog->meta_description     = $request->meta_description;
        $blog->og_title             = $request->og_title;
        $blog->og_description       = $request->og_description;
        $blog->twitter_title        = $request->twitter_title;
        $blog->twitter_description  = $request->twitter_description;
        $blog->keywords             = $request->meta_keywords;
        $blog->save();

        flash('Blog '.trans('messages.created_msg'))->success();
        return redirect()->route('blog.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = getActiveLanguage();
        $blog  = Blog::findOrFail($id);
        return view('backend.blogs.edit', compact('blog','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'image' => 'required',
            'description' => 'required',
        ]);

        $blog = Blog::findOrFail($id);

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = Blog::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $blog->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
        }

        $blog->name                 = $request->name;
        $blog->status               = $request->status;
        $blog->slug                 = $slug;
        $blog->image                = $request->image;
        $blog->blog_date            = $request->blog_date ?? date('Y-m-d');
        $blog->description          = $request->description;
        $blog->meta_title           = $request->meta_title;
        $blog->meta_description     = $request->meta_description;
        $blog->og_title             = $request->og_title;
        $blog->og_description       = $request->og_description;
        $blog->twitter_title        = $request->twitter_title;
        $blog->twitter_description  = $request->twitter_description;
        $blog->keywords             = $request->meta_keywords;
        $blog->save();
        
        flash(trans('messages.blog').trans('messages.updated_msg'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::destroy($id);

        flash(trans('messages.blog').trans('messages.deleted_msg'))->success();
        return redirect()->route('blog.index');
    }

    public function updateStatus(Request $request)
    {
        $blog = Blog::findOrFail($request->id);
        
        $blog->status = $request->status;
        $blog->save();
       
        return 1;
    }
}
