<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      \App::bind("\App\Etsy\EtsyAPI", function() {

        // Use the API key and secret for the signed in user
        $user = auth()->user();
        $keys = $user->apiKeys;
        $key = $keys->key;
        $secret = $keys->secret;
        return new \App\Etsy\EtsyAPI($key, $secret);
      });

    }
}
