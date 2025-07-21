@extends('layouts.app')
@section('title', 'Order Details- '.env('APP_NAME'))
@section('content')
    <section class="container mx-auto py-12 px-6 lg:px-12">
        <div class="flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-6xl">
                @if (!empty($order))
                    <!-- Header -->
                    <div class="flex justify-between items-start gap-5 flex-wrap sm:flex-wrap md:flex-nowrap xl:flex-nowrap">
                        <div class="flex items-start">

                            <div>
                                <div class="flex flex-wrap sm:flex-wrap md:flex-nowrap xl:flex-nowrap">
                                    <h1 class="text-2xl font-semibold">Order #{{ $order->code }}</h1>

                                    @if ($order->delivery_status == 'delivered')
                                        <span class="px-3 py-1 rounded-full text-sm text-[#48a839] bg-green-100 ml-5">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @elseif($order->delivery_status == 'cancelled')
                                        <span class="px-3 py-1 rounded-full text-sm text-[#ff0000] bg-red-100  ml-5">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm bg-yellow-200 text-yellow-800 my-2 xl:my-0  ml-0 md:ml-5 xl:ml-5">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-gray-700 mukta-medium">
                                    {{ date('M d, Y h:i A', $order->date) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex">
                            <a class="flex items-center gap-2 justify-center px-4 py-2 text-sm font-bold text-white transition-all duration-200 border border-[#41b6e8] bg-[#41b6e8] rounded-lg shadow-md hover:bg-[#3498db] hover:shadow-lg" href="{{ route('download-invoice',['order_id' => $order->id]) }}">
                                Invoice
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="mt-3 flex justify-between items-center">
                        <div class="flex space-x-2">
                            @if ($order->delivery_status == 'pending')
                                @if ($order->cancel_request == 0)
                                    <button
                                        class="cancel-order-btn flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#ffffff]  hover:bg-[#41b6e8] " onclick="openCancelModal({{ $order->id }})">{{ trans('messages.cancel') . ' ' . trans('messages.order') }}</button>
                                @else
                                    @if ($order->cancel_approval == 0)
                                        <span style="color: red;">{{ trans('messages.cancel_request_send') }}</span>
                                    @elseif ($order->cancel_approval == 2)
                                        <span style="color: red;">Cancel request rejected!</span>
                                    @endif
                                    
                                @endif

                            @endif

                            @if ($order->delivery_status == 'delivered')
                                @php
                                    $returnDeadline = \Carbon\Carbon::parse($order->delivery_completed_date)->addDays(get_setting('default_return_time') ?? 1);
                                    $now = \Carbon\Carbon::now();
                                    $isReturnEligible = $now->lessThanOrEqualTo($returnDeadline);

                                    // // Check if all products are already requested for return
                                    // $allReturned = $order->orderDetails->every(function ($orderDetail) {
                                    //     return $orderDetail->returns()->exists(); // Check if this product has a return request
                                    // });

                                    $allReturned = $order->orderDetails->every(function ($orderDetail) {
                                            $orderedQty = $orderDetail->quantity;
                                            $returnedQty = $orderDetail->returns->sum('return_qty'); // sum of return_qty from related returns

                                            return $returnedQty >= $orderedQty;
                                        });
                                    
                                @endphp

                                @if ($isReturnEligible && !$allReturned )
                                    <button class="return-order-btn flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#ffffff]  hover:bg-[#41b6e8] " data-order-id="{{ $order->id }}" onclick="openReturnModal()">
                                        {{ trans('messages.return') }}
                                    </button>
                                @elseif ($allReturned)
                                    <p class="text-green-600 font-semibold">All products in this order have been requested for return.</p>
                                @else
                                    <p class="text-red-500 font-semibold">Return period expired on {{ $returnDeadline->format('d M, Y') }}.</p>
                                @endif
                               
                            @endif

                           
                        </div>
                    </div>

                    <!-- Additional Products -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h2 class="text-xl font-semibold mb-4">Item Details</h2>
                        <div class="space-y-6">
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    // Check if this product has a return request
                                    $returnRequest = $orderDetail->returns()->latest()->first(); // Get the latest return request for the product
                                @endphp
                                <div class=" border-b md:pb-3">
                                    <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                        <a href="{{ route('products.show', ['slug' => $orderDetail->product->slug]) }}"
                                            class="shrink-0 md:order-1">
                                            <img class="h-20 w-20 dark:block rounded-md"
                                                src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}"
                                                alt="imac image" />
                                        </a>

                                        <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                            <a href="{{ route('products.show', ['slug' => $orderDetail->product->slug]) }}"
                                                class="text-base font-medium text-gray-900 hover:underline text-black">{{ $orderDetail->product->getTranslation('name', $lang) }}</a>
                                            x {{ $orderDetail->quantity }}
                                            {{-- @if ($order->delivery_status == 'delivered')
                                                @if ($returnRequest)
                                                    <p>Return Status: 
                                                        <span class="text-sm
                                                            @if($returnRequest->status == 'pending') text-yellow-500
                                                            @elseif($returnRequest->status == 'approved') text-green-500
                                                            @else text-red-500 @endif">
                                                            {{ ucfirst($returnRequest->status) }}
                                                        </span>
                                                    </p>
                                                @else
                                                    <p>No return request for this product.</p>
                                                @endif
                                            @endif --}}
                                        </div>
                                        <div class="flex items-center justify-between md:order-3 md:justify-end ">
                                            <div class="text-end md:order-4 md:w-32">
                                                <p class="text-base text-lg font-semibold text-gray-900 text-black">
                                                    @if (($orderDetail->og_price * $orderDetail->quantity) != $orderDetail->price)
                                                        <span class="text-gray-500 line-through text-xs">
                                                            {{ single_price($orderDetail->og_price * $orderDetail->quantity) }}</span> <br>
                                                    @endif
                                                    {{ single_price($orderDetail->price) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                        <div class="flex justify-between text-gray-600 mb-3">
                            <p>Sub Total</p>
                            <p>{{ single_price($order->sub_total) }}</p>
                        </div>

                        <div class="flex justify-between text-gray-800 mb-3">
                            <p>VAT</p>
                            <p>{{ single_price($order->orderDetails->sum('tax')) }}</p>
                        </div>
                        @if ($order->offer_discount != 0)
                            <div class="flex justify-between text-gray-600 mb-3">
                                <p>Discount</p>
                                <p>- {{ single_price($order->offer_discount) }}</p>
                            </div>
                        @endif

                        @if ($order->coupon_discount != 0)
                            <div class="flex justify-between text-gray-600 mb-3">
                                <p>Coupon Discount</p>
                                <p>- {{ single_price($order->coupon_discount) }}</p>
                            </div>
                        @endif

                        <div class="flex justify-between text-gray-800 mb-3">
                            <p>Shipping Charges</p>
                            <p>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</p>
                        </div>

                        <hr>

                        <div class="mb-2 mt-3 flex justify-between">
                            <p class="text-base text-default-700">Total</p>
                            <p class="text-base font-bold text-default-700">{{ single_price($order->grand_total) }}
                            </p>
                        </div>
                        <!-- Payment Method -->
                        <div class="mt-3 border-t border-gray-200 pt-4">
                            <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                            <p class="text-gray-600">
                                @if ($order->payment_type == 'cod')
                                    Cash On Delivery
                                @else
                                    {{ strtoupper($order->payment_type) }}
                                @endif
                            </p>
                        </div>

                        @if ($order->order_notes != '')
                            <!-- Order notes -->
                            <div class="mt-3 border-t border-gray-200 pt-4">
                                <h2 class="text-xl font-semibold mb-4">Order Note</h2>
                                <p class="text-gray-600">
                                    {{ $order->order_notes }}
                                </p>
                            </div>
                        @endif
                        
                    </div>

                    <div class="mb-4 flex flex-wrap sm:flex-wrap md:flex-nowrap xl:flex-nowrap gap-4">
                        <!-- Billing Address -->
                        <div class="md:w-1/2 mt-6 border-t border-gray-200 pt-4">
                            <h2 class="text-xl font-semibold mb-4">Billing Address</h2>
                            @php
                                $billing_address = json_decode($order->billing_address);
                            @endphp

                            @if ($billing_address)
                                <p class="text-gray-600">{{ $billing_address->name }}</p>
                                <p class="text-gray-600">{{ $billing_address->address }},</p>
                                <p class="text-gray-600">{{ $billing_address->city }}, {{ $billing_address->state }}</p>
                                <p class="text-gray-600">{{ $billing_address->country }} - {{ $billing_address->zipcode }}
                                </p>
                                <p class="text-gray-600">{{ $billing_address->email }}, {{ $billing_address->phone }}</p>
                            @endif
                        </div>

                        <!-- Shipping Address -->
                        <div class="md:w-1/2 mt-6 border-t border-gray-200 pt-4">

                            <h2 class="text-xl font-semibold mb-4">Shipping Address</h2>
                            @php
                                $shipping_address = json_decode($order->shipping_address);
                            @endphp

                            @if ($shipping_address)
                                <p class="text-gray-600">{{ $shipping_address->name }}</p>
                                <p class="text-gray-600">{{ $shipping_address->address }},</p>
                                <p class="text-gray-600">{{ $shipping_address->city }}, {{ $shipping_address->state }}</p>
                                <p class="text-gray-600">{{ $shipping_address->country }} -
                                    {{ $shipping_address->zipcode }}</p>
                                <p class="text-gray-600">{{ $billing_address->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <!-- Order History Timeline -->
                    <div id="track-order" class="mt-6 border-t space-y-4 pt-4">
                        <h2 class="text-xl font-semibold mb-4">Track Order</h2>

                        <ol class="relative border-s border-gray-300 ">
                            @if (!empty($dataByStatus))
                                @foreach ($dataByStatus as $dStat)
                                    <li class="mb-6 ms-4">
                                        <div
                                            class="absolute w-3 h-3 bg-[#41b6e8] rounded-full mt-1.5 -start-1.5 border border-white">
                                        </div>
                                        <time class="mb-1 text-md font-normal leading-none font-semibold text-black">
                                            {{ ucfirst(str_replace('_', ' ', $dStat['status'])) }}
                                        </time>
                                        <h3 class="mb-1 text-md text-gray-500">{{ $dStat['date'] }}</h3>
                                    </li>
                                @endforeach
                            @endif


                        </ol>
                    </div>


                    @if ($order->orderReturns->count() > 0)
                    <div class="card border-t">
                        <div class="card-header">
                            <h2 class="text-xl font-semibold mb-4">Order Return Requests Details</h2>
                        </div>
                        <div class="card-body">
                            <div class="mt-8">
                                <table class="w-full border-collapse border text-sm">
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
                                                            $return->status === 'pending' ? 'bg-orange-500' : 
                                                            ($return->status === 'approved' ? 'bg-green-500' : 
                                                            'bg-red-500') 
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
                    <!-- Footer -->
                @endif
            </div>
        </div>

        <!-- Return Order Modal -->
        <div id="returnModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
                <h2 class="text-xl font-bold mb-2">Return Order</h2>
                <p class="text-sm text-gray-600 mb-4">Select the products you want to return</p>

                <form id="returnForm">
                    <!-- Dynamic Return Product List -->
                    <div class="max-h-80 overflow-y-auto border rounded">
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 border text-left">Select</th>
                                    <th class="p-2 border text-left">Product</th>
                                    <th class="p-2 border text-center">Ordered Qty</th>
                                    <th class="p-2 border text-center">Return Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $productsAvailableForReturn = false; // Flag to check if any product is eligible for return
                                @endphp
                                @foreach ($order->orderDetails as $orderDetail)
                                    
                                    @php
                                        $totalReturnedQty = $orderDetail->returns->sum('return_qty');
                                        $remainingQty = $orderDetail->quantity - $totalReturnedQty;
                                    @endphp

                                    {{-- @if ($orderDetail->product->return_refund == 1 && $orderDetail->returns->isEmpty()) --}}
                                    @if ($orderDetail->product->return_refund == 1 && $remainingQty > 0)
                                        @php
                                            $productsAvailableForReturn = true;
                                        @endphp
                                        <tr class="border">
                                            <td class="p-2 text-center">
                                                <input type="checkbox" class="return-modal-checkbox" data-id="{{ $orderDetail->id }}" data-max="{{ $remainingQty }}">
                                            </td>
                                            <td class="p-2">
                                                <div class="font-medium">
                                                    {{ $orderDetail->product->getTranslation('name', $lang) }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Already returned: {{ $totalReturnedQty }}<br>
                                                    Remaining: {{ $remainingQty }}
                                                </div>
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ $orderDetail->quantity }}
                                            </td>
                                            <td class="p-2 text-center">
                                                <input type="number" class="border px-2 py-1 w-20 return-qty"
                                                    name="return_qty[{{ $orderDetail->id }}]" min="1" max="{{ $remainingQty }}" 
                                                    value="1" disabled>
                                            </td>
                                        </tr>
                                    @else

                                    @endif
                                @endforeach
                                <!-- Show message if no products are available for return -->
                                @if (!$productsAvailableForReturn)
                                    <tr>
                                        <td colspan="4" class="text-center text-red-500 p-4">
                                            No products are available for return in this order.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Return Reason -->
                    <label class="block mt-4 text-sm font-semibold">Return Reason:</label>
                    <textarea class="w-full border rounded p-2 mt-1" placeholder="Enter reason" id="returnReason" name="returnReason" required></textarea>

                    <!-- Submit & Close Buttons -->
                    <div class="mt-4 text-right space-x-2">
                        <button type="button" onclick="closeReturnModal()" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                        <button id="returnSubmit" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Submit Return
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cancel Order Modal -->
        <div id="cancelModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-2">Cancel Order</h2>
                <p class="text-sm text-gray-600 mb-4">Please provide a reason for cancellation.</p>

                <form id="cancelForm">
                    <input type="hidden" id="cancelOrderId" value="">

                    <!-- Cancellation Reason -->
                    <label class="block text-sm font-semibold">Cancellation Reason:</label>
                    <textarea id="cancelReason" class="w-full border rounded p-2 mt-1" placeholder="Enter reason" required></textarea>

                    <!-- Submit & Close Buttons -->
                    <div class="mt-4 text-right space-x-2">
                        <button type="button" onclick="closeCancelModal()" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Close
                        </button>
                        <button type="button" id="cancelSubmit" class="bg-red-500 text-white px-4 py-2 rounded">
                            Cancel Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </section>
@endsection

@section('script')
    <script>
        function openReturnModal() {
            document.getElementById("returnModal").classList.remove("hidden");
        }

        function closeReturnModal() {
            document.getElementById("returnModal").classList.add("hidden");
        }

       // Enable quantity input only if the product is selected
        document.querySelectorAll(".return-modal-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                let input = this.closest("tr").querySelector(".return-qty");
                input.disabled = !this.checked;
            });
        });

        document.getElementById("returnSubmit").addEventListener("click", function(e) {
            e.preventDefault();  // Prevent form from submitting the usual way
            
            // Collect form data
            let selectedProducts = {};
            let validReturnQty = true;

            document.querySelectorAll(".return-modal-checkbox:checked").forEach(checkbox => {
                let productId = checkbox.dataset.id;
                let returnQty = checkbox.closest("tr").querySelector(".return-qty").value;
                let maxQty = checkbox.dataset.max;  // Get the max quantity allowed for return

                // Ensure that return quantity is not empty and valid (greater than 0)
                if (returnQty === "" || parseInt(returnQty) <= 0) {
                    validReturnQty = false;
                    toastr.error("Please enter a valid return quantity for all selected products.", 'Error');
                }

                // Ensure that the return quantity does not exceed the available quantity
                if (parseInt(returnQty) > parseInt(maxQty)) {
                    validReturnQty = false;
                    toastr.error("Return quantity cannot exceed the ordered quantity for the product.", 'Error');
                }

                selectedProducts[productId] = returnQty;
            });

            let returnReason = document.querySelector("textarea[placeholder='Enter reason']").value;
            let orderId = "{{ $order->id }}"; // Ensure the order ID is dynamically passed in

            // Validation check: Ensure at least one product is selected and return reason is provided
            if (Object.keys(selectedProducts).length === 0) {
                toastr.error("Please select at least one product to return.", 'Error');
                return;
            }

            if (returnReason.trim() === "") {
                toastr.error("Please enter a return reason.", 'Error');
                return;
            }

            // If return quantity is not valid, don't proceed
            if (!validReturnQty) {
                return;
            }

            // Prepare data to send
            let formData = {
                order_id: orderId,
                return_reason: returnReason,
                return_qty: selectedProducts
            };

            // Send data using fetch (AJAX)
            fetch("{{ route('order.return') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"  // Include CSRF token
                },
                body: JSON.stringify(formData)  // Convert the data to JSON
            })
            .then(response => response.json())  // Parse the JSON response
            .then(data => {
                if (data.success) {
                    toastr.success("Return request submitted successfully!", 'Success');
                    closeReturnModal();  // Close modal on success
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    toastr.error("Something went wrong. Try again.", 'Error');
                }
            })
            .catch(error => {
                toastr.error("There was an error submitting your request.", 'Error');
                console.error("Error:", error);
            });
        });

        function openCancelModal(orderId) {
            document.getElementById("cancelOrderId").value = orderId;
            document.getElementById("cancelModal").classList.remove("hidden");
        }

        function closeCancelModal() {
            document.getElementById("cancelModal").classList.add("hidden");
        }

        document.getElementById("cancelSubmit").addEventListener("click", function(e) {
            e.preventDefault();

            let orderId = document.getElementById("cancelOrderId").value;
            let cancelReason = document.getElementById("cancelReason").value.trim();

            if (cancelReason === "") {
                toastr.error("Please enter a cancellation reason.", "Error");
                return;
            }

            fetch("{{ route('order.cancel') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: orderId,
                    cancel_reason: cancelReason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    toastr.success("Order cancel request send successfully!", "Success");
                    closeCancelModal();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000); // Refresh the page to reflect the changes
                } else {
                    toastr.error("Something went wrong. Try again.", "Error");
                }
            })
            .catch(error => {
                toastr.error("Error cancelling order.", "Error");
                console.error("Error:", error);
            });
        });

    </script>
@endsection
