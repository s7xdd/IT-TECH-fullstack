@extends('layouts.app')

@section('title', 'My Address - '.env('APP_NAME'))

@section('content')

    <div class="container mx-auto py-12 px-6 lg:px-12">

        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl text-left">
            Addresses</span>
        </h2>


        <a href="{{ route('add-address') }}" class=" w-52 ml-auto rounded-md  px-4 py-2 flex justify-center items-center duration-200  bg-[#41b6e8] group hover:bg-[#41b6e8] hover:text-white">
            <svg class="w-[20px] fill-dark group-hover:fill-white pr-1" viewBox="0 0 426.66667 426.66667"
                xmlns="http://www.w3.org/2000/svg" id="fi_1828925">
                <path
                    d="m405.332031 192h-170.664062v-170.667969c0-11.773437-9.558594-21.332031-21.335938-21.332031-11.773437 0-21.332031 9.558594-21.332031 21.332031v170.667969h-170.667969c-11.773437 0-21.332031 9.558594-21.332031 21.332031 0 11.777344 9.558594 21.335938 21.332031 21.335938h170.667969v170.664062c0 11.777344 9.558594 21.335938 21.332031 21.335938 11.777344 0 21.335938-9.558594 21.335938-21.335938v-170.664062h170.664062c11.777344 0 21.335938-9.558594 21.335938-21.335938 0-11.773437-9.558594-21.332031-21.335938-21.332031zm0 0">
                </path>
            </svg>
            Add New Address
        </a>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-5">
            @if (!empty($addresses[0]))
                @foreach ($addresses as $key => $address)
                    <!-- address -->
                    <div class="flex-1 duration-150 border hover:shadow-lg" id="address_div_{{ $address->id }}">
                        <div class="flex items-center flex-wrap xl:flex-nowrap gap-y-3 justify-between border-b p-3 xl:-p-0">
                            <p class="flex items-center gap-2 pl-3 text-base font-medium text-nowrap">
                                <svg width="18px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 359.31 409.6">
                                    <path
                                        d="M187.74,0c9.63,1.8,19.46,2.9,28.86,5.54,26.51,7.45,48.3,22.4,65.64,43.76,12.87,15.85,21.65,33.83,25.95,53.8,7.51,34.89,2.51,68-16.57,98.23-32.96,52.23-66.53,104.07-99.84,156.08-.5.78-.99,1.58-1.52,2.35-5.73,8.33-15.44,8.38-21.02-.07-6.23-9.44-12.23-19.04-18.33-28.57-27.47-42.94-55.08-85.8-82.37-128.86-17.91-28.27-24.2-59.16-18.54-92.19C59.34,55.69,99.25,14.33,153.29,2.72,159.36,1.41,165.6.89,171.77,0,177.09,0,182.41,0,187.74,0ZM239.62,131.85c-.09-33.02-27.18-59.99-60.12-59.84-32.81.15-59.59,27.08-59.62,59.94-.03,33.01,26.98,60.07,59.92,60.04,32.95-.03,59.91-27.13,59.82-60.14Z"
                                        style="fill: #000; stroke-width: 0px;"></path>
                                </svg>
                                Address #{{ $key + 1 }}
                            </p>
                            @if ($address->set_default == 1)
                                <span class="px-3 py-1 rounded-full text-sm text-[#bf6d00] bg-orange-100 ml-5">
                                    Default
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full text-sm text-[#48a839] bg-green-100 ml-5">
                                {{ ucfirst($address->type) }}
                            </span>

                            <a href="{{ route('edit-address',['id'=> $address->id]) }}"
                                class="px-3 py-2 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#ffffff]  hover:bg-[#41b6e8]">Edit
                                Address</a>

                            <button type="button"
                                class="w-[42px] h-[38px] flex justify-center items-center duration-200 group bg-[#41b6e8] hover:bg-[#41b6e8] delete-address-btn" data-address-id="{{ $address->id }}">
                                <svg class="w-[18px] fill-[#fff] group-hover:fill-white" id="fi_3096673"
                                    enable-background="new 0 0 512 512" viewBox="0 0 512 512"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="m424 64h-88v-16c0-26.467-21.533-48-48-48h-64c-26.467 0-48 21.533-48 48v16h-88c-22.056 0-40 17.944-40 40v56c0 8.836 7.164 16 16 16h8.744l13.823 290.283c1.221 25.636 22.281 45.717 47.945 45.717h242.976c25.665 0 46.725-20.081 47.945-45.717l13.823-290.283h8.744c8.836 0 16-7.164 16-16v-56c0-22.056-17.944-40-40-40zm-216-16c0-8.822 7.178-16 16-16h64c8.822 0 16 7.178 16 16v16h-96zm-128 56c0-4.411 3.589-8 8-8h336c4.411 0 8 3.589 8 8v40c-4.931 0-331.567 0-352 0zm313.469 360.761c-.407 8.545-7.427 15.239-15.981 15.239h-242.976c-8.555 0-15.575-6.694-15.981-15.239l-13.751-288.761h302.44z">
                                        </path>
                                        <path
                                            d="m256 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z">
                                        </path>
                                        <path
                                            d="m336 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z">
                                        </path>
                                        <path
                                            d="m176 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z">
                                        </path>
                                    </g>
                                </svg>
                            </button>

                        </div>
                        <div class="p-3 bg-[#F4F4F4]">

                            <table>
                                <tbody>
                                    <tr class="">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>Name :</div>
                                        </td> --}}
                                        <td class="text-base text-black">{{ $address->name }}</td>
                                    </tr>

                                    <tr class="mb-5">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>Address :</div>
                                        </td> --}}
                                        <td class="text-base text-black">
                                            {!! nl2br($address->address) !!}
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>City :</div>
                                        </td> --}}
                                        <td class="text-base text-black"> {{ $address->city }}</td>
                                    </tr>
                                    <!-- <tr class="mb-5">
                                            <td class="text-sm w-[150px] mb-5">
                                                <div>Emirate :</div>
                                            </td>
                                            <td class="text-base text-black">Dubai</td>
                                        </tr> -->
                                    <tr class="mb-5">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>Zip :</div>
                                        </td> --}}
                                        <td class="text-base text-black"> {{ $address->postal_code }}</td>
                                    </tr>
                                    <tr class="mb-5">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>Country :</div>
                                        </td> --}}
                                        <td class="text-base text-black"> {{ $address->country_name }}</td>
                                    </tr>
                                    <tr class="mb-5">
                                        {{-- <td class="text-sm w-[250px] mb-5">
                                            <div>Phone Number : </div>
                                        </td> --}}
                                        <td class="text-base text-black">
                                            {{ $address->phone }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('click', '.delete-address-btn', function() {
            let addressId = $(this).data('address-id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/address/delete',
                        type: 'DELETE',
                        data: {
                            address_id: addressId,
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $('#address_div_'+addressId).hide();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(response.message, "{{trans('messages.error')}}");
                            }
                        },
                        error: function(xhr) {
                            toastr.error(response.message, "Failed to delete");
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
