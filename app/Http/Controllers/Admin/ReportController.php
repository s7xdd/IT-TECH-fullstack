<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Search;
use App\Models\Order;
use App\Exports\OrdersExport;
use App\Exports\StockExport;
use App\Exports\WishExport;
use App\Exports\SearchExport;
use Auth;
use Session;
use Excel;


class ReportController extends Controller
{
    
    public function sales_report(Request $request){
        if(!empty($request->all())){
            Session::put('sales_report_filter', $request->all());
        }else{
            Session::forget('sales_report_filter');
        }
        
        $request->session()->put('sales_report_last_url', url()->full());
        $shop_search    = ($request->has('shop_search')) ? $request->shop_search : '';
        
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = Order::orderBy('id', 'desc');
        if(Auth::user()->user_type == 'staff' && Auth::user()->shop_id != NULL){
            $orders->where('shop_id', Auth::user()->shop_id);
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($shop_search) {
            $orders = $orders->where('shop_id', $shop_search);
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.reports.sales', compact('orders', 'sort_search', 'delivery_status', 'date','shop_search'));
    }
    
     public function sales_orders_show($id)
    {
        
        $order = Order::findOrFail(decrypt($id));
        return view('backend.reports.sales_orders_show', compact('order'));
    }
    
    
    public function exportSalesReport()
    {
        $data = [];
        if(Session::has('sales_report_filter')){
            $data = Session::get('sales_report_filter');
        }
    
        $keyword = (isset($data['search']) && $data['search'] != '') ? $data['search'] : null;
        $date = (isset($data['date']) && $data['date'] != '') ? $data['date'] : null;
        $delivery_status = (isset($data['delivery_status']) && $data['delivery_status'] != '') ? $data['delivery_status'] : null;
        
        $from_date = $to_date = null;
        if ($date != null) {
            $from_date = date('Y-m-d', strtotime(explode(" to ", $date)[0]));
            $to_date = date('Y-m-d', strtotime(explode(" to ", $date)[1]));
        }
        
        
        return Excel::download(new OrdersExport($keyword, $from_date, $to_date, $delivery_status), 'sales_report_'. now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
    
    public function stock_report(Request $request)
    {
        if(!empty($request->all())){
            Session::put('stock_report_filter', $request->all());
        }else{
            Session::forget('stock_report_filter');
        }
        $sort_by =null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
       
        if ($request->has('category_id') && $request->category_id !== '0') {
            $sort_by = $request->category_id;
            $childIds = [];
            $categoryfilter = $request->category_id;
            $childIds[] = array($request->category_id);
            
            if($categoryfilter != ''){
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products','sort_by','sort_search'));
    }
    public function exportStockReport()
    {
        $data = [];
        if(Session::has('stock_report_filter')){
            $data = Session::get('stock_report_filter');
        }
       
        $category_id = (isset($data['category_id']) && $data['category_id'] != '') ? $data['category_id'] : null;
        $keyword = (isset($data['search']) && $data['search'] != '') ? $data['search'] : null;
        
        return Excel::download(new StockExport($category_id, $keyword), 'stock_report_'. now()->format('Y-m-d_H-i-s') .'.xlsx');
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products','sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by =null;
        $sellers = Seller::orderBy('created_at', 'desc');
        if ($request->has('verification_status')){
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers','sort_by'));
    }

    public function wish_report(Request $request)
    {
        if(!empty($request->all())){
            Session::put('wish_report_filter', $request->all());
        }else{
            Session::forget('wish_report_filter');
        }
        $sort_by =null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
        
        if ($request->has('category_id') && $request->category_id !== '0') {
            $sort_by = $request->category_id;
            $childIds = [];
            $categoryfilter = $request->category_id;
            $childIds[] = array($request->category_id);
            
            if($categoryfilter != ''){
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products','sort_by'));
    }
    
    public function exportWishReport()
    {
        $data = [];
        if(Session::has('wish_report_filter')){
            $data = Session::get('wish_report_filter');
        }
       
        $category_id = (isset($data['category_id']) && $data['category_id'] != '') ? $data['category_id'] : null;
        $keyword = (isset($data['search']) && $data['search'] != '') ? $data['search'] : null;
        
        return Excel::download(new WishExport($category_id, $keyword), 'wishlist_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function user_search_report(Request $request){
         if(!empty($request->all())){
            Session::put('search_report_filter', $request->all());
        }else{
            Session::forget('search_report_filter');
        }
        $date = $request->date;

        $query = Search::latest();

        if ($date != null) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $searches = $query->with(['user'])->paginate(10);
        return view('backend.reports.user_search_report', compact('searches','date'));
    }
    
    public function exportSearchReport(){
        $data = [];
        if(Session::has('search_report_filter')){
            $data = Session::get('search_report_filter');
        }
       
        $date = (isset($data['date']) && $data['date'] != '') ? $data['date'] : null;
        $from_date = $to_date = null;
        if ($date != null) {
            $from_date = date('Y-m-d', strtotime(explode(" to ", $date)[0]));
            $to_date = date('Y-m-d', strtotime(explode(" to ", $date)[1]));
        }
        
        
        return Excel::download(new SearchExport($from_date, $to_date), 'search_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
    
    public function commission_history(Request $request) {
        $seller_id = null;
        $date_range = null;
        
        if(Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        } if($request->seller_id) {
            $seller_id = $request->seller_id;
        }
        
        $commission_history = CommissionHistory::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id){
            
            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }
        
        $commission_history = $commission_history->paginate(10);
        if(Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }
    
    public function wallet_transaction_history(Request $request) {
        $user_id = null;
        $date_range = null;
        
        if($request->user_id) {
            $user_id = $request->user_id;
        }
        
        $users_with_wallet = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id){
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }
        
        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }
}
