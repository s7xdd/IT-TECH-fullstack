@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="h6">User Search Report</h1>
                </div>
                <div class="card-body">

                    <form action="{{ route('user_search_report.index') }}" method="GET">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <div class="form-group mb-0">
                                    <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="Filter by date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                                </div>
                            </div>
                        
                            <div class="col-auto">
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-warning">Filter</button>
                                    <a class="btn btn-info" href="{{ route('user_search_report.index') }}" >Reset</a>
                                    <a href="{{ route('export.search_report') }}"  class="btn btn-danger" style="border-radius: 30px;">Excel Export</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Search Key</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searches as $key => $searche)
                                <tr>
                                    <td>{{ $key + 1 + ($searches->currentPage() - 1) * $searches->perPage() }}</td>
                                    <td>{{ $searche->query }}</td>
                                    <td>
                                        @if ($searche->user_id)
                                            {{ $searche->user->name }}
                                            {{-- <a
                                                href="{{ route('user_search_report.index', ['user_id' => $searche->user_id]) }}">
                                                
                                            </a> --}}
                                        @else
                                            GUEST
                                        @endif
                                    </td>
                                    <td>{{ $searche->ip_address }}</td>
                                    <td>{{ $searche->created_at->format('d-m-Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        {{ $searches->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
