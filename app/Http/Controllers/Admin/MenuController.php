<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function builder()
    {
        return view('backend.menu.builder');
    }
}
