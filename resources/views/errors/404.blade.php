@extends('layouts.app')
@section('content')
    <div class="row flex justify-center items-center min-h-screen">
        <div class="col-xl-10 mx-auto">
            <div class=" content-center">
                <div class="error-page text-center">
                    <img src="{{ asset('assets/img/403.svg') }}" alt="404" class="svg" style="height: 600px;">
                    <!-- <div class="error-page__title">404</div> -->
                    <h5 class="fw-500 mt-2"><b>404 | Page Not Found</b></h5>

                </div>
            </div>
        </div>
    </div>
@endsection
