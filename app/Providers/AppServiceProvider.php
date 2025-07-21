<?php

namespace App\Providers;

use App\Models\Service;
use Efectn\Menu\Models\Menus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        Schema::defaultStringLength(191);

        Blade::component('components.home.blog-list', 'blogList');
        Blade::component('components.home.testimonials', 'testimonials');
        Blade::component('components.footer', 'footer');


        View::composer('components.navigation.header', function ($view) {
            $menu = Menus::where('name', 'header')->with('items')->first();
            $services = Service::where('status', 1)->orderBy('sort_order', 'ASC')->get();
            $view->with(['menu_items' => $menu ? $menu->items : [], 'services' => $services]);
        });

        View::composer('components.navigation.footer', function ($view) {
            $menu = Menus::where('name', 'footer')->with('items.child')->first();
            $bottom_footer = Menus::where('name', 'bottom footer')->with('items')->first();
            $view->with([
                'menu_items' => $menu ? $menu->items : [],
                'bottom_footer' => $bottom_footer ? $bottom_footer->items : [],
            ]);
        });
        Paginator::useBootstrap();
    }
}
