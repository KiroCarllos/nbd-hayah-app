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
