<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        Validator::extend('image64', function ($attribute, $value, $parameters, $validator) {
            if ($value) {
                $type = explode('/',
                    explode(':',
                        substr($value, 0, strpos($value, ';'))
                    )[1]
                )[1];
                if (in_array($type, $parameters)) {
                    return true;
                }
            }

            return false;
        });

        Validator::replacer('image64', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(',', $parameters), $message);
        });

        $this->app->bind('translator', function($app) {
            $translator = new \App\CustomOverrides\Translator($app['translation.loader'], $app['config']['app.locale']);
            $translator->setFallback($app['config']['app.fallback_locale']);

            return $translator;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();

        Cashier::ignoreMigrations();

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
