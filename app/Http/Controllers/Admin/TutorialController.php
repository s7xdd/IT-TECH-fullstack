<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TutorialController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_tutorials', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $tutorials = Tutorial::orderBy('tutorial_date', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $tutorials = $tutorials->where('name', 'like', '%' . $sort_search . '%');
        }
        $tutorials = $tutorials->paginate(15);
        return view('backend.tutorials.index', compact('tutorials', 'sort_search'));
    }

    public function create()
    {
        return view('backend.tutorials.create');
    }
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
        $same_slug_count    = Tutorial::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $tutorials                       = new Tutorial;
        $tutorials->name                 = $request->name ?? NULL;
        $tutorials->slug                 = $slug;
        $tutorials->image                = $request->image;
        $tutorials->description          = $request->description ?? NULL;
        $tutorials->tutorial_date       = $request->tutorial_date ?? date('Y-m-d');
        $tutorials->status               = $request->status;
        $tutorials->meta_title           = $request->meta_title;
        $tutorials->meta_description     = $request->meta_description;
        $tutorials->og_title             = $request->og_title;
        $tutorials->og_description       = $request->og_description;
        $tutorials->twitter_title        = $request->twitter_title;
        $tutorials->twitter_description  = $request->twitter_description;
        $tutorials->keywords             = $request->meta_keywords;
        $tutorials->save();

        flash('Tutorial ' . trans('messages.created_msg'))->success();
        return redirect()->route('tutorial.index');
    }

    public function edit(Request $request, $id)
    {
        $lang = getActiveLanguage();
        $tutorial  = Tutorial::findOrFail($id);
        return view('backend.tutorials.edit', compact('tutorial', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'image' => 'required',
            'description' => 'required',
        ]);

        $tutorial = Tutorial::findOrFail($id);

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = Tutorial::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $tutorial->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
        }

        $tutorial->name                 = $request->name;
        $tutorial->status               = $request->status;
        $tutorial->slug                 = $slug;
        $tutorial->image                = $request->image;
        $tutorial->tutorial_date            = $request->tutorial_date ?? date('Y-m-d');
        $tutorial->description          = $request->description;
        $tutorial->meta_title           = $request->meta_title;
        $tutorial->meta_description     = $request->meta_description;
        $tutorial->og_title             = $request->og_title;
        $tutorial->og_description       = $request->og_description;
        $tutorial->twitter_title        = $request->twitter_title;
        $tutorial->twitter_description  = $request->twitter_description;
        $tutorial->keywords             = $request->meta_keywords;
        $tutorial->save();

        flash(trans('messages.tutorial') . trans('messages.updated_msg'))->success();
        return back();
    }
    public function destroy($id)
    {
        Tutorial::destroy($id);

        flash(trans('messages.tutorial') . trans('messages.deleted_msg'))->success();
        return redirect()->route('tutorial.index');
    }

    public function updateStatus(Request $request)
    {
        $tutorial = Tutorial::findOrFail($request->id);

        $tutorial->status = $request->status;
        $tutorial->save();

        return 1;
    }
}
