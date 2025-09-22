<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\FirebaseFcm::class, function ($app) {
            return new \App\Services\FirebaseFcm($app->make(\Psr\Log\LoggerInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register currency helper as Blade directive
        Blade::directive('currency', function ($amount) {
            return "<?php echo App\Helpers\CurrencyHelper::format($amount); ?>";
        });

        Blade::directive('currencyPayment', function ($amount) {
            return "<?php echo App\Helpers\CurrencyHelper::formatPayment($amount); ?>";
        });
    }
}
