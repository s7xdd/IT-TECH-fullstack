@extends('layouts.app')
@section('title', 'Faq - '.env('APP_NAME'))
@section('content')
<div class="container mx-auto py-12 px-6 lg:px-12">
   <h1 class="text-3xl font-bold text-center mb-8">{{ $page->getTranslation('title',$lang) }}</h1>
<div class="mb-4 border-b border-gray-200">
   <ul class="flex flex-wrap  -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
      @foreach ($faq_categories as $key => $category)
         <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 text-[#41b6e8] !active:text-[#41b6e8] !hover:text-[#41b6e8] font-bold rounded-t-lg" id="faq-{{$category->slug}}" data-tabs-target="#{{$category->slug}}" type="button" role="tab" aria-controls="{{$category->slug}}" @if($key == 0) aria-selected="true" @else aria-selected="false" @endif>{{$category->name}}</button>
         </li>
      @endforeach
    
   </ul>
</div>
<div id="default-tab-content">

      @foreach ($faq_categories as $category)
         <div class="hidden py-4 rounded-lg bg-gray-50" id="{{$category->slug}}" role="tabpanel" aria-labelledby="faq-{{$category->slug}}">
            <div class="mx-auto space-y-4">
               @if (!empty($category->faq_list[0]))
                  @foreach ($category->faq_list as $faq)
                     <div class="bg-white p-6 rounded-lg shadow">
                        <button class="w-full flex justify-between items-center text-left font-semibold text-lg" onclick="toggleFAQ('faq_{{$faq->id}}', this)">
                           <span>{{ $faq->question }}</span>
                           <span class="arrow">+</span>
                        </button>
                        <p id="faq_{{$faq->id}}" class="mt-2 text-gray-600 hidden transition-all duration-300 ease-in-out">
                           {{ $faq->answer }}
                        </p>
                     </div>
                  @endforeach
               @else
                  <span class="ml-4">No Data Found</span>
               @endif
            </div>
         </div>
           
      @endforeach

   
   
</div>
</div>
@endsection

@section('script')
<script>
   function toggleFAQ(id, btn) {
            const faq = document.getElementById(id);
            const arrow = btn.querySelector('.arrow');
            
            if (faq.classList.contains('hidden')) {
                faq.classList.remove('hidden');
                faq.style.maxHeight = faq.scrollHeight + 'px';
                arrow.textContent = '-';
            } else {
                faq.classList.add('hidden');
                faq.style.maxHeight = '0px';
                arrow.textContent = '+';
            }
      }
</script>
@endsection