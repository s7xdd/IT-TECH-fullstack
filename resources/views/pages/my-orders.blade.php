@extends('layouts.app')

@section('title', 'My Address - '.env('APP_NAME'))

@section('content')

    <div class="container mx-auto py-12 px-6 lg:px-12">

        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl text-left">
            My Orders</span>
        </h2>

        <div class="relative overflow-x-auto block mt-6">
            <table class="w-full text-sm text-left text-gray-500 rtl:text-right whitespace-nowrap">
                <thead class="text-xs text-gray-700 uppercase bg-[#F4F4F4] text-[#603813]">
                    <tr>
                        <th scope="col" class="text-center px-6 py-3">#</th>
                        <th scope="col" class="text-center px-6 py-3">Order Code</th>
                        <th scope="col" class="text-center px-6 py-3">Total Products</th>
                        <th scope="col" class="text-center px-6 py-3">Date</th>
                        <th scope="col" class="text-center px-6 py-3">Status</th>
                        <th scope="col" class="text-center px-6 py-3">Price</th>
                        <th scope="col" class="text-center px-6 py-3 text-center"><span class="">Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($orderList[0]))
                        @foreach ($orderList as $key => $order)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="text-center px-6 py-8 font-normal whitespace-nowrap">{{$key+1}}</th>
                                <td class="text-center px-6 py-5 font-medium text-gray-900 whitespace-nowrap leading-4">
                                    {{$order->code}}
                                </td>
                                <td class="text-center px-6 py-8 font-normal text-gray-900 whitespace-nowrap">
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td class="text-center px-6 py-8 font-normal text-gray-900 whitespace-nowrap">
                                    {{ date('d-m-Y H:i A',strtotime($order->created_at))}}
                                </td>
                                <td class="text-center px-6 py-8 font-normal text-gray-900 whitespace-nowrap">
                                    @if ($order->delivery_status == 'delivered')
                                        <span class="p-2 text-sm text-[#48a839] bg-green-100 rounded">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @elseif($order->delivery_status == 'cancelled')
                                        <span class="p-2 text-sm text-[#ff0000] bg-red-100 rounded">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @else
                                        <span class="p-2 text-sm bg-yellow-200 text-yellow-800 rounded">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                        </span>
                                    @endif
                                    
                                </td>
                                <td class="text-center px-6 py-8 font-normal text-gray-900 whitespace-nowrap">
                                    {{ env('DEFAULT_CURRENCY') }} {{$order->grand_total}}
                                </td>
                                <td class="text-center px-6 py-8 text-center">
                                    <a href="{{route('order-details',['code'=> $order->code])}}" class="font-normal text-white bg-[#41b6e8] hover:bg-[#3498db] hover:text-black px-3 py-2 hover:underline"
                                        type="button">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>



    @endsection
