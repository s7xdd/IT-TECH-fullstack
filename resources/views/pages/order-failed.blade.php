@extends('layouts.app')
 @section('title', 'W - '.env('APP_NAME'))
 @section('content')
 <div class="container mx-auto py-12 px-6 lg:px-12">
 <div class="bg-white rounded-lg shadow-lg p-3 lg:p-6 max-w-md mx-auto ">
    <div class="text-center">
       <svg class="w-[100px] mx-auto mb-5" version="1.1" id="fi_190406" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 507.2 507.2" style="enable-background:new 0 0 507.2 507.2;" xml:space="preserve">
          <circle style="fill:#F15249;" cx="253.6" cy="253.6" r="253.6"></circle>
          <path style="fill:#AD0E0E;" d="M147.2,368L284,504.8c115.2-13.6,206.4-104,220.8-219.2L367.2,148L147.2,368z"></path>
          <path style="fill:#FFFFFF;" d="M373.6,309.6c11.2,11.2,11.2,30.4,0,41.6l-22.4,22.4c-11.2,11.2-30.4,11.2-41.6,0l-176-176
             c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L373.6,309.6z"></path>
          <path style="fill:#D6D6D6;" d="M280.8,216L216,280.8l93.6,92.8c11.2,11.2,30.4,11.2,41.6,0l23.2-23.2c11.2-11.2,11.2-30.4,0-41.6
             L280.8,216z"></path>
          <path style="fill:#FFFFFF;" d="M309.6,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6L197.6,373.6
             c-11.2,11.2-30.4,11.2-41.6,0l-22.4-22.4c-11.2-11.2-11.2-30.4,0-41.6L309.6,133.6z"></path>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
          <g>
          </g>
       </svg>
       <h2 class="text-2xl text-red-500 font-bold mb-2">Order Failed</h2>
       <p class="text-gray-600 mb-4">Oops! Your Order Could Not Be Processed.</p>
    </div>
    
    <div class="text-center mt-6">
       <div class="flex gap-2">
         <a class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-white transition-all border  border-[#41b6e8] duration-200 bg-[#41b6e8]  hover:text-[#000000]  hover:bg-[#41b6e8]" href="{{ route('products.index') }}">Continue Shopping</a>
       </div>
    </div>
 </div>
 </div>
 @endsection