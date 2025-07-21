@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mb-4">
        <h1 class="h3">Menu Builder</h1>
    </div>

    <div class="card">
        <div class="card-body">
           {!! Menu::render() !!}
        </div>
    </div>
@endsection

@section('script')
    {!! Menu::scripts() !!}
@endsection
