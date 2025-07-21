@extends('backend.layouts.app')

@section('content')

<div class="card">
    
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Return Requests</h5>
            </div>
        </div>

        <div class="card-body">
            
                <form class="" action="" id="sort_orders" method="GET">
                    <div class="row">
                        
                        <div class="col-lg-3 mt-2">
                            <div class="form-group mb-2">
                                <label>Order Code</label>
                                <input type="text" class="form-control" id="search" name="search"@isset($search) value="{{ $search }}" @endisset placeholder="Type Order code & hit Enter">
                            </div>
                        </div>

                        <div class="col-lg-3 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Approval Status</label>
                                <select id="ca_search" name="ca_search" class="form-control" >
                                    <option {{ ($ca_search == '') ? 'selected' : '' }} value="">Select status</option>
                                    <option {{ ($ca_search == 'pending') ? 'selected' : '' }} value="pending">Pending</option>
                                    <option {{ ($ca_search == 'approved') ? 'selected' : '' }} value="approved">Approved</option>
                                    <option {{ ($ca_search == 'rejected') ? 'selected' : '' }} value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Date</label>
                                <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="Filter by request date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                            </div>
                        </div>

                     

                        <div class="col-auto mt-3">
                            <div class="form-group mb-0" style="margin: inherit;">
                                <button type="submit" class="btn btn-info">Filter</button>
                                <a href="{{ route('return_requests.index') }}" class="btn  btn-cancel">{{trans('messages.reset')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            <hr>

            <table class="table aiz-table mb-2">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Order Code</th>
                        <th class="w-25">Product</th>
                        <th class="text-center">Return Qty</th>
                        <th class="w-25">Reason</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">View Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr class="">
                            <td class="text-center">{{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}</td>
                            <td class=" text-center">{{ $order->order->code }}</td>
                            <td class="">{{ $order->product->name }}</td>
                            <td class=" text-center">{{ $order->return_qty }}</td>
                            <td class="">{{ $order->return_reason }}</td>
                            <td class=" text-center">
                                @if($order->status == 'pending')
                                    <button onclick="updateReturnStatus({{ $order->id }}, 'approved')"  class="btn btn-success d-innline-block">Approve</button>
                                    <button onclick="updateReturnStatus({{ $order->id }}, 'rejected')"  class="btn btn-danger d-innline-block">Reject</button>
                                @elseif($order->status == 'approved')
                                    <span class="badge badge-lg badge-inline bg-success">
                                        {{ $order->status }}
                                    </span>
                                @else
                                    <span class="badge badge-lg badge-inline bg-danger">
                                        {{ $order->status }}
                                    </span>
                                @endif
                            </td>
                            <td class=" text-center">
                                <a class="btn btn-soft-primary btn-icon btn-circle" href="{{route('return_orders.show', encrypt($order->order->id))}}" title="View">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function updateReturnStatus(returnId, status) {
        swal({
                title: "Are you sure?",
                text: `Are you sure you want to mark this return as ${status}?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('return-request-status') }}",
                    type: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: JSON.stringify({ return_id: returnId, status: status }),

                    success: function(response) {
                        swal("Successfully updated!", {
                            icon: "success",
                        });
                        setTimeout(function () { 
                            location.reload(true); 
                        }, 3000); 
                    }
                });
            }else{
                $(this).val('');
            }
        });
    }
</script>
@endsection
