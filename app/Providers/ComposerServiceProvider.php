<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Model\Setting;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    
    public function boot() {

        //view()->composer("front.page.terms-conditions","App\Http\ViewComposers\TestViewComposer");
        //view()->composer("front.page.privacy-policy","App\Http\ViewComposers\TestViewComposer");
        View::composer(
            ['*'], 'App\Http\ViewComposers\TestViewComposer'
         );
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}