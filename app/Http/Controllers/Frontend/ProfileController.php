<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use App\Models\Cart;
use Hash;
use Illuminate\Support\Facades\File;
use Storage;
use Auth;

class ProfileController extends Controller
{
    public function counters()
    {
        return response()->json([
            'cart_item_count' => Cart::where('user_id', auth()->user()->id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', auth()->user()->id)->count(),
            'order_count' => Order::where('user_id', auth()->user()->id)->count(),
        ]);
    }

    public function getUserAccountInfo(){
        $lang = getActiveLanguage();
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        $user = User::find($user_id);
        
        return view('pages.my-profile',compact('lang','user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|min:9',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
    

        session()->flash('message', trans('messages.profile_update_success'));
        session()->flash('alert-type', 'success');

        return redirect()->back();
    }

    public function updatePassword(){
        $lang = getActiveLanguage();
        return view('pages.change-password',compact('lang'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('message', trans('messages.current_password_incorrect'));
            session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('message', trans('messages.password_updated_successfully'));
        session()->flash('alert-type', 'success');
        return redirect()->back();
    }

    public function orderList(Request $request){
        $lang = getActiveLanguage();
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id);
            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($request->delivery_status != null) {
                $orders = $orders->where('delivery_status', $request->delivery_status);
                $delivery_status = $request->delivery_status;
            }
           
            $total_count = $orders->count();
            $orderList = $orders->get();
        }
        return view('pages.my-orders',compact('orderList','total_count','lang'));
    }
    
    public function orderReturnList(Request $request){
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id)->where('return_request',1);
           
            $orderList = $orders->get();
        }
        return view('frontend.order-returns',compact('orderList'));
    }
    public function orderDetails(Request $request){
        $order_code = $request->code ?? '';
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $track_list = [];
        $lang = getActiveLanguage();
        $order = [];

        if($order_code != ''){
            $order = Order::where('code',$order_code)->where('user_id',$user_id)->first();
            if($order){
                $tracks = OrderTracking::where('order_id', $order->id)->orderBy('id','ASC')->get();
                
                if ($tracks) {
                    foreach ($tracks as $key=>$value) {
                        $temp = array();
                        $temp['id'] = $value->id;
                        $temp['status'] = $value->status;
                        $temp['date'] = date("d-m-Y h:i A", strtotime($value->status_date));
                        $track_list[] = $temp;
                    }
                }    
            }
        }

        if(!empty($track_list)){
            $dataByStatus = $track_list;
        }else{
            $dataByStatus = [];
        }
        
        // echo '<pre>';
        // print_r($track_list);
        // print_r($dataByStatus);
        // die;

        return view('pages.order-details',compact('lang','order','track_list','dataByStatus'));
    }

    public function getUserAddressInfo(){
        $lang = getActiveLanguage();
        $addresses = Address::where('user_id', auth()->user()->id)->orderBy('id','desc')->get();
        return view('pages.my-address', compact('addresses','lang'));
    }

    public function addAddress(){
        $lang = getActiveLanguage();
        return view('pages.add-address', compact('lang'));
    }

    public function saveAddress(Request $request){
        $validate = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/']
        ], [
            'name.regex' => 'Only alphabets and spaces are allowed in the name field.',
            'phone.regex' => 'Please enter a valid phone number (numbers only, 7-15 digits).'
        ]);

        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';

        if($user_id != ''){
            if($request->address_id != ''){
                $address                = Address::find($request->address_id);
            }else{
                $address                = new Address;
            }
            if($request->default == 1){
                Address::where('user_id', $user_id)->update(['set_default' => 0]);
            }
            
            $address->user_id       = $user_id;
            $address->address       = $request->address ?? null;
            $address->name          = $request->name ?? null;
            $address->city          = $request->city ?? null;
            $address->state_name    = $request->state ?? null;
            $address->country_name  = $request->country ?? null;
            $address->postal_code   = $request->zipcode ?? null;
            $address->type          = $request->address_type ?? null;
            $address->set_default   = $request->default ?? 0;
            $address->phone         = $request->phone;
            $address->save();
    
            session()->flash('message', 'Address saved successfully!');
            session()->flash('alert-type', 'success');
            return redirect()->route('my-address');
        }else{
            session()->flash('message', 'Something went wrong!');
            session()->flash('alert-type', 'error');
            return redirect()->back();
        }
    }

    public function deleteAddress(Request $request){
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $address_id = $request->address_id ?? null;
        if($user_id != '' && $address_id != null){
            Address::where(['id' => $request->address_id,'user_id' => $user_id])->delete();
            return response()->json([
                'status' => true,
                'message' => trans('messages.address').' '.trans('messages.deleted_msg')
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.something_went_wrong')
            ]);
        }
    }

    public function editAddress($id){
        $lang = getActiveLanguage();
        $address = Address::find($id);
        return view('pages.add-address', compact('address','lang'));
    }
}
