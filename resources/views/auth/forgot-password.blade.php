@extends('layouts.app')

@section('title', 'Forgot Password - '.env('APP_NAME'))

@section('content')

<div class="container mx-auto py-12 px-6 lg:px-12">
    <div class="max-w-2xl m-auto bg-white shadow-2xl rounded-2xl p-8 transform transition duration-300">
    <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-6">Forgot Password</h1>
        
        <p class="text-gray-600 text-center mb-4">Enter your email address to receive a password reset link.</p>
        
        <form id="forgotPasswordForm" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter registered email.." class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
            </div>
            
            <button type="submit"  id="resetButton" class="w-full bg-primary text-white py-4 rounded-full hover:bg-[#3498db] transition-all duration-300 transform hover:scale-105 font-medium text-sm flex items-center justify-center gap-2">
                <span id="resetText">Reset</span>
                <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
        </form>
        
        <p class="text-center text-gray-700 mt-6 text-sm">Remembered your password? <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login</a></p>
    </div>
    </div>

@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#forgotPasswordForm').on('submit', function(e) {
            e.preventDefault();

            // Show loading effect
            $('#resetButton').prop('disabled', true);
            $('#resetText').text('Processing...');
            $('#loadingSpinner').removeClass('hidden');

            $.ajax({
                url: "{{ route('password.sendResetLink') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success(response.success);
                    $('#forgotPasswordForm')[0].reset();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors && errors.email) {
                        toastr.error(errors.email[0]);
                    } else if (xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    }
                },
                complete: function() {
                    // Hide loading effect
                    $('#resetButton').prop('disabled', false);
                    $('#resetText').text('Reset');
                    $('#loadingSpinner').addClass('hidden');
                }
            });
        });
    });
</script>
@endsection

