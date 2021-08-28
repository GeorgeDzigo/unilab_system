<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\ItemLog;
use App\Models\PeoplePosition;
use App\Observers\ItemLogObserver;
use App\Observers\ItemObserver;
use App\Observers\PeoplePositionObserver;
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
    public function boot()
    {
        if(!$this->app->environment('local')) {
            \URL::forceScheme('https');
        }
        Item::observe(ItemObserver::class);
        ItemLog::observe(ItemLogObserver::class);
        PeoplePosition::observe(PeoplePositionObserver::class);
    }

}
