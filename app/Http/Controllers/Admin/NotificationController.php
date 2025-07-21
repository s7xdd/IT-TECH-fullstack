<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index() {
        $notifications = auth()->user()->notifications()->paginate(15);
        
        auth()->user()->unreadNotifications->markAsRead();
        
        return view('backend.notification.index', compact('notifications'));
        
    }
}
