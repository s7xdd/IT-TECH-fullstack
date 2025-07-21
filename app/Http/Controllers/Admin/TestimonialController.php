<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class TestimonialController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonials::orderBy('sort_order','asc')->paginate(15);

        return view('backend.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'comment' => 'required',
            'sort_order' => 'nullable|integer',
            'status' => 'required',
        ]);
        $data = [
            'name'=> $request->name,
            'title'=> $request->designation,
            'comment'=> $request->comment,
            'sort_order' => ($request->sort_order != '') ? $request->sort_order : 0,
            'status' => $request->status
        ];

        $testimonial = Testimonials::create($data);
       
        return redirect()->route('testimonials.index')->with([
            'status' => "Testimonial created successfully"
        ]);
    }

    public function edit(Testimonials $testimonial)
    {
        return view('backend.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonials $testimonial)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'comment' => 'required',
            'sort_order' => 'nullable|integer',
            'status' => 'required',
        ]);

        $testimonial->name          = $request->name;
        $testimonial->title         = $request->title;
        $testimonial->comment       = $request->comment;
        $testimonial->sort_order    = ($request->sort_order != '') ? $request->sort_order : 0;
        $testimonial->status        = $request->status;
        $testimonial->save();

        return redirect()->route('testimonials.index')->with([
            'status' => "Testimonial details Updated"
        ]);
    }

    public function destroy($id)
    {
        Testimonials::destroy($id);

        flash('Testimonial '.trans('messages.deleted_msg'))->success();
        return redirect()->route('testimonials.index');
    }

    public function updateStatus(Request $request)
    {
        $test = Testimonials::findOrFail($request->id);

        $test->status = $request->status;
        if ($test->save()) {
            return 1;
        }
        return 0;
    }

}
