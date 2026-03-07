<?php
// ============================================================
// app/Providers/AppServiceProvider.php
// ============================================================
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        // @activeClass('route/pattern') directive untuk sidebar
        Blade::directive('activeClass', function ($expression) {
            return "<?php echo request()->is({$expression}) ? 'active' : ''; ?>";
        });
    }
}