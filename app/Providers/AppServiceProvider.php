<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\EmpleadoRepositoryInterface::class,
            \App\Repositories\EmpleadoRepository::class
        );

       $this->app->bind(
            \App\Interfaces\DepartamentoRepositoryInterface::class,
            \App\Repositories\DepartamentoRepository::class
        ); 

        $this->app->bind(
            \App\Interfaces\NominaMetricasRepositoryInterface::class,
            \App\Repositories\NominaMetricasRepository::class
        );

        $this->app->bind(
            \App\Interfaces\HistorialNominaRepositoryInterface::class,
            \App\Repositories\HistorialNominaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
