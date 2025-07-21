<div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm   sm:p-6">
   <form class="space-y-4" id="applyCouponForm" method="POST">
         <div>
            <label for="voucher" class="mb-2 block text-xl font-medium text-gray-900"> Coupon Code</label>
            <input type="text" class="block w-full py-3 rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"  id="couponCode" name="couponCode" placeholder="Enter Coupon Code" value="{{ $response['summary']['coupon_code'] }}"
                placeholder="" required />

            <input type="hidden" name="coupon_action" id="coupon_action" value="@if ($response['summary']['coupon_applied'] == 1) remove @else add @endif ">
         </div>
         <div id="couponMessage"></div>
         <button type="submit"  class="discount__submit flex bg-[#4d4d4f] py-4 w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">@if ($response['summary']['coupon_applied'] == 1) Remove @else Apply @endif</button>
    </form>
    
</div>