<?php

namespace App\Providers;

use App\Models\Compania;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Producto;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $compania = Compania::first(); // O ajusta la consulta según tu necesidad
            $view->with('compania', $compania);
        });
    }
}
