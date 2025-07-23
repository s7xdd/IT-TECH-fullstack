<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSliderController extends Controller
{

    public function index()
    {
        $sliders = HomeSlider::orderBy('sort_order', 'ASC')->paginate(15);
        return view('backend.home_sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('backend.home_sliders.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            // 'banner' => 'required',
            // 'mobile_banner' => 'required',
            // 'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'video_url' => 'nullable|url',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('sliders/videos', 'public');
        }

        HomeSlider::create([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
            'video_url' => $request->video_url,
            'video_file' => $videoPath,
        ]);


        Cache::forget('homeSlider');

        flash(trans('messages.slider') . ' ' . trans('messages.created_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    public function edit(HomeSlider $homeSlider)
    {
        return view('backend.home_sliders.edit', compact('homeSlider'));
    }

    public function update(Request $request, HomeSlider $homeSlider)
    {
        $request->validate([
            'name' => 'required',
            // 'banner' => 'required',
            // 'mobile_banner' => 'required',
            // 'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'video_url' => 'nullable|url',
            'remove_video' => 'nullable|boolean',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);


        $updateData = [
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
            'video_url' => $request->video_url,
        ];

        if ($request->has('remove_video') && $request->remove_video) {
            if ($homeSlider->video_file && Storage::disk('public')->exists($homeSlider->video_file)) {
                Storage::disk('public')->delete($homeSlider->video_file);
            }
            $updateData['video_file'] = null;
        }

        if ($request->hasFile('video_file')) {
            if ($homeSlider->video_file && Storage::disk('public')->exists($homeSlider->video_file)) {
                Storage::disk('public')->delete($homeSlider->video_file);
            }
            $videoPath = $request->file('video_file')->store('sliders/videos', 'public');
            $updateData['video_file'] = $videoPath;
        }

        $homeSlider->update($updateData);

        Cache::forget('homeSlider');

        flash(trans('messages.slider') . ' ' . trans('messages.updated_msg'))->success();
        return redirect()->route('home-slider.index');
    }


    public function destroy($id)
    {
        $slider = HomeSlider::findOrFail($id);

        if ($slider->video_file && Storage::disk('public')->exists($slider->video_file)) {
            Storage::disk('public')->delete($slider->video_file);
        }

        HomeSlider::destroy($id);
        Cache::forget('homeSlider');

        flash(trans('messages.slider') . ' ' . trans('messages.deleted_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    public function updateStatus(Request $request)
    {
        $slider = HomeSlider::findOrFail($request->id);
        Cache::forget('homeSlider');
        $slider->status = $request->status;
        if ($slider->save()) {
            return 1;
        }
        return 0;
    }
}
