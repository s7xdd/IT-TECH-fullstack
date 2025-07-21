<p class="text-xl font-semibold text-gray-900 text-black">Order summary</p>
<div class="space-y-4">
    <div class="space-y-2">
        <dl class="flex items-center justify-between gap-4">
            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Sub total</dt>
            <dd class="text-base font-medium text-gray-900 text-black">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['sub_total'] }}</dd>
        </dl>

        <dl class="flex items-center justify-between gap-4">
            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">VAT</dt>
            <dd class="text-base font-medium text-gray-900 text-black">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['vat_amount'] }}</dd>
        </dl>

        @if ($response['summary']['discount'] != 0)
            <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Discount</dt>
                <dd class="text-base font-medium text-green-600">-{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['discount'] }}</dd>
            </dl>
        @endif
        
        @if ($response['summary']['coupon_discount'] != 0)
            <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Coupon Discount</dt>
                <dd class="text-base font-medium text-green-600">-{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['coupon_discount'] }}</dd>
            </dl>
        @endif
        
        
        <dl class="flex items-center justify-between gap-4">
            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Shipping Charge</dt>
            <dd class="text-base font-medium text-gray-900 text-black">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['shipping'] }}</dd>
        </dl>
    </div>
    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 ">
        <dt class="text-base font-bold text-gray-900 text-black">Total</dt>
        <dd class="text-base font-bold text-gray-900 text-black">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['total'] }}</dd>
    </dl>
</div>