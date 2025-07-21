@extends('layouts.app')
@section('title', 'Faq - '.env('APP_NAME'))
@section('content')
<div class="container mx-auto py-12 px-6 lg:px-12">
   <h1 class="text-3xl mb-8 text-center">My Profile</h1>

   <div class="flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-md p-4 xl:p-8 w-full max-w-4xl">
                
                <div class="mt-3 flex justify-end items-center">
                    <div class="flex space-x-2">
                        <a class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#41b6e8]  hover:bg-[#d7f0db] " href="{{ route('update-password') }}">Change Password</a>
                    </div>
                </div>

                <form class="contact-page__form" action="{{ route('account.update') }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="input-item flex space-x-2.5 mb-5 mt-3">

                        <div class="w-full h-full">
                            <div class="w-full h-full input-com"><label class="input-label capitalize block  mb-2 text-gray text-[13px] font-normal">Full Name</label>
                                <div class="relative w-full h-full overflow-hidden border input-wrapper !border-gray-300 !dark:border-gray-400">
                                    <input placeholder="Enter Name" name="name" class="input-field placeholder:text-sm text-sm px-5 text-dark-gray w-full font-normal bg-white focus:ring-0 focus:outline-none h-[40px]" type="text" value="{{old('name', $user['name'])}}">
                                    
                                </div>
                                @error('name')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="input-item flex space-x-2.5 mb-5 mt-5">
                        <div class="w-full h-full">
                            <div class="w-full h-full input-com"><label class="input-label capitalize block  mb-2 text-qgray text-[13px] font-normal">Email</label>
                                <div class="relative w-full h-full overflow-hidden border input-wrapper border-gray-border ">
                                    <input disabled="" class="input-field placeholder:text-sm text-sm px-5 text-dark-gray w-full font-normal bg-white focus:ring-0 focus:outline-none h-[40px]" type="email" value="{{ $user['email']}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="input-item flex space-x-2.5 mb-5 mt-5">

                        <div class="w-full h-full">
                            <div class="w-full h-full input-com"><label class="input-label capitalize block  mb-2 text-qgray text-[13px] font-normal">Phone
                                    Number*</label>
                                <div class="relative w-full h-full overflow-hidden border input-wrapper border-gray-border ">
                                    <input readonly="" placeholder="Enter phone" class="input-field placeholder:text-sm text-sm px-5 text-dark-gray w-full font-normal bg-white focus:ring-0 focus:outline-none h-[40px]" type="tel" name="phone" value="{{old('phone', $user['phone'])}}">
                                    
                                </div>
                                @error('phone')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex justify-between items-center">
                        <div class="flex space-x-2">
                            <a class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#41b6e8]  hover:bg-[#d7f0db] " href="{{ route('account') }}">Cancel</a>
                            <button class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white transition-all border  border-[#41b6e8] duration-200 bg-[#41b6e8]  hover:text-[#41b6e8]  hover:bg-[#d7f0db]" type="submit">Update profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

</div>
@endsection

@section('script')
    @if(session()->has('message'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
                toastr.{{ session('alert-type', 'info') }}("{{ session('message') }}");
            });
        </script>
    @endif

    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });
    </script>
@endsection