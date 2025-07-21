<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\FaqCategory;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::orderBy('sort_order','asc')->get();
        return view('backend.faq_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.faq_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:faq_categories,name']);

        $slug               = Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = FaqCategory::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        FaqCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => $request->status,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('faq_categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(Request $request, $id)
    {
        $faqCategory  = FaqCategory::findOrFail($id);
        return view('backend.faq_categories.edit', compact('faqCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $faq_category = FaqCategory::findOrFail($id);

        $slug               = Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = FaqCategory::where('slug', 'LIKE', $slug. '%')->where('id', '!=', $id)->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $faq_category->name         = $request->name;
        $faq_category->is_active    = $request->status;
        $faq_category->slug         = $slug;
        $faq_category->sort_order   = $request->sort_order;
        $faq_category->save();

        return redirect()->route('faq_categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        FaqCategory::destroy($id);

        flash('FAQ Category '.trans('messages.deleted_msg'))->success();
        return redirect()->route('faq_categories.index');
    }

    public function getAllCategoryFaqs($id){
        $faqs = Faq::where('faq_category_id', base64_decode($id))->orderBy('sort_order', 'asc')->get();
        $category = FaqCategory::find(base64_decode($id));
        return view('backend.faq_categories.faqs', compact('faqs','id','category'));
    }

    public function updateFaq(Request $request){
        
        $data = $request->input('faqs');

        if($data){
            Faq::where('faq_category_id', base64_decode($request->category_id))->delete();
            foreach ($data as $faq) {
                if($faq['question'] != NULL && $faq['answer'] != NULL){
                    Faq::create([
                        'faq_category_id' => base64_decode($request->category_id),
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['order'],
                    ]);
                }
            }
        }

        flash('FAQs Updated')->success();
        return redirect()->route('faq_categories.index');
    }

}
