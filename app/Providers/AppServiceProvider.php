<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactPage;
use App\View\Components\Recusive;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function getCategory($parentId)
    {
        //nav bar
        $data = Category::where('category_status', 0)->orderBy('category_order', 'asc')->get();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusiveHome($parentId);
        return $htmlOption;
    }

    public function getCategoryOption($parentId)
    {
        //form search product
        $data = Category::where('category_status', 0)->orderBy('category_order', 'asc')->get();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parentId);
        return $htmlOption;
    }
    
    public function boot()
    {
        view()->composer('pages.*', function ($view) {
            $htmlOption = $this->getCategory($parentId = '');
            $option_search = $this->getCategoryOption($parentId = '');
            $category = Category::where('category_parentId', 0)->orderBy('category_order', 'asc')->get();
            $contact = ContactPage::first();
            $view->with('category_navbar', $htmlOption)
                ->with('option_search', $option_search)->with('category', $category)->with('contact', $contact);
        });
        if (env('APP_ENV') === 'production') {
            $this->app['request']->server->set('HTTPS', 'on'); // this line
            URL::forceScheme('https');
        }
    }
}
