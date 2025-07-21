<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Support\Str;
use DB;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_services', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $services = Service::with('parent')->orderBy('sort_order', 'asc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $services = $services->where('name', 'like', '%' . $sort_search . '%');
        }

        $services = $services->paginate(15);

        return view('backend.services.index', compact('services', 'sort_search'));
    }

    public function create()
    {
        $services = Service::whereNull('parent_id')->get();

        $all_services = Service::whereNull('parent_id')->get();

        return view('backend.services.create', compact('services', 'all_services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug = Str::lower($slug);
        $same_slug_count = Service::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . ($same_slug_count + 1) : '';
        $slug .= $slug_suffix;

        $service = new Service;
        $service->name = $request->name ?? NULL;
        $service->custom_fields = json_encode($request->custom_fields);
        $service->slug = $slug;
        $service->price = $request->price ?? 0;
        $service->image = $request->image;
        $service->status = $request->status;
        $service->sort_order = $request->sort_order;
        $service->parent_id = $request->parent_id ?? null;

        $service->save();

        $service_translation = ServiceTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'service_id' => $service->id]);
        $service_translation->name = $request->name;
        $service_translation->short_description = $request->short_description;
        $service_translation->description = $request->description;
        $service_translation->features = $request->features;
        $service_translation->meta_title = $request->meta_title;
        $service_translation->meta_description = $request->meta_description;
        $service_translation->meta_keywords = $request->meta_keywords;
        $service_translation->og_title = $request->og_title;
        $service_translation->og_description = $request->og_description;
        $service_translation->twitter_title = $request->twitter_title;
        $service_translation->twitter_description = $request->twitter_description;
        $service_translation->save();

        flash('Service ' . trans('messages.created_msg'))->success();
        return redirect()->route('service.index');
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $service = Service::findOrFail($id);
        $all_services = Service::whereNull('parent_id')->where('id', '!=', $id)->get();

        return view('backend.services.edit', compact('service', 'lang', 'all_services'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        $service = Service::findOrFail($id);

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = Service::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $service->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . ($same_slug_count + 1) : '';
            $slug .= $slug_suffix;
        }

        if ($request->lang == env("DEFAULT_LANGUAGE", 'en')) {
            $service->name = $request->name;
            $service->custom_fields = json_encode($request->custom_fields);
            $service->status = $request->status;
            $service->slug = $slug;
            $service->price = $request->price ?? 0;
            $service->image = $request->image;
            $service->icon = $request->icon;
            $service->sort_order = $request->sort_order;
            $service->parent_id = $request->parent_id ?? null;
            $service->save();
        }

        $service_translation = ServiceTranslation::firstOrNew(['lang' => $request->lang, 'service_id' => $service->id]);
        $service_translation->name = $request->name;
        $service_translation->description = $request->description;
        $service_translation->short_description = $request->short_description;
        $service_translation->features = $request->features;
        $service_translation->meta_title = $request->meta_title;
        $service_translation->meta_description = $request->meta_description;
        $service_translation->meta_keywords = $request->meta_keywords;
        $service_translation->og_title = $request->og_title;
        $service_translation->og_description = $request->og_description;
        $service_translation->twitter_title = $request->twitter_title;
        $service_translation->twitter_description = $request->twitter_description;
        $service_translation->save();

        flash(trans('messages.service') . trans('messages.updated_msg'))->success();
        return redirect()->route('service.index');
    }

    public function destroy($id)
    {
        Service::destroy($id);
        flash(trans('messages.service') . trans('messages.deleted_msg'))->success();
        return redirect()->route('service.index');
    }

    public function updateStatus(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->status = $request->status;
        $service->save();
        return 1;
    }
}
