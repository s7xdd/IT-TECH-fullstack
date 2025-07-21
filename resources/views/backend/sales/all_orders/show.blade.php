@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-info" href="{{ Session::has('last_url') ? Session::get('last_url') : route('all_orders.index') }}" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp

               

                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">Payment Status</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid
                        </option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid
                        </option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">Delivery Status</label>
                    @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                Pending</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                Confirmed</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                Picked Up</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                On The Way</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                Delivered</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                Cancel</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                    @endif
                </div>
                <div class="col-md-3 ml-auto d-none">
                    <label for="update_tracking_code">Tracking Code (optional)</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="{{ $order->tracking_code }}">
                </div>
            </div>
            <div class="mb-3">
                
            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">
                    @php
                        $shipping_address = json_decode($order->shipping_address);
                    @endphp
                    @if ($shipping_address)
                        <address>
                            <strong class="text-main">{{ $shipping_address->name }}</strong><br>
                            {{ $shipping_address->email }}<br>
                            {{ $shipping_address->phone }}<br>
                            {{ $shipping_address->address }},
                            {{ $shipping_address->city }}, {{ $shipping_address->state }}, {{ $shipping_address->country }},
                            <br>
                            {{ $shipping_address->zipcode }}
                        </address>
                    @endif
                    
                    
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Status</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Date </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    $returnRequest = $orderDetail->returns()->latest()->first(); // Get the latest return request for the product
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong class="text-muted fs-13">{{ $orderDetail->product->name }}</strong>
                                            {{-- <small> --}}
                                                @if ($orderDetail->variation != null)
                                                    @php
                                                        $variations = json_decode($orderDetail->variation);
                                                    
                                                    @endphp
                                                    <ul>
                                                        @foreach($variations as $var)
                                                        <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                        @if ($order->delivery_status == 'delivered')
                                            {{-- @if ($returnRequest)
                                                <p><br><b>Return Status</b>: 
                                                    <span class="badge badge-lg badge-inline 
                                                        @if($returnRequest->status == 'pending') bg-warning
                                                        @elseif($returnRequest->status == 'approved') bg-success
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($returnRequest->status) }}
                                                    </span>
                                                </p>
                                            @else
                                                <br><p>No return request for this product.</p>
                                            @endif --}}
                                        @endif
                                    </td>
                                   
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if ($orderDetail->og_price != $orderDetail->offer_price)
                                            <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                        @endif
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">Sub Total :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>

    @if ($order->orderReturns->count() > 0)
        <div class="card">
            <div class="card-header">
                <h1 class="h2 fs-16 mb-0">Order Return Requests Details</h1>
            </div>
            <div class="card-body">
                <div class="mt-8">
                    <table class=" table aiz-table ">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border text-left">#</th>
                                <th class="p-2 border text-left">Product</th>
                                <th class="p-2 border text-center">Return Qty</th>
                                <th class="p-2 border text-left">Reason</th>
                                <th class="p-2 border text-left">Date</th>
                                <th class="p-2 border text-center">Status</th>
                                <th class="p-2 border text-center">Balance Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderReturns as $key => $return)
                                @php
                                    $orderDetail = $return->orderDetail;
                                    $orderedQty = $orderDetail->quantity ?? 0;

                                    // Calculate total returned up to this return request (including current one)
                                    $totalReturnedUpToNow = $orderDetail->returns()
                                        ->where('id', '<=', $return->id) // Assumes auto-increment ID is sequential
                                        ->sum('return_qty');

                                    $balanceAtThisReturn = $orderedQty - $totalReturnedUpToNow;
                                @endphp
                                <tr class="border">
                                    <td class="p-2 border">
                                        {{ $key+1 }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ $return->product->name ?? 'Product not found' }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        {{ $return->return_qty }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ $return->return_reason }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ date('d M, Y H:i A', strtotime($return->created_at)) }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        <span class="inline-block px-2 py-1 rounded-full text-white 
                                            {{ 
                                                $return->status === 'pending' ? 'bg-primary' : 
                                                ($return->status === 'approved' ? 'bg-success' : 
                                                'bg-danger') 
                                            }}">
                                            {{ $return->status }}
                                        </span>
                                    </td>
                                    <td class="p-2 border text-center text-blue-600 font-semibold">
                                        {{ $balanceAtThisReturn }} left
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        

        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Delivery status has been updated');
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Payment status has been updated');
            });
        });

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', 'Order tracking code has been updated');
            });
        });
    </script>
@endsection
