<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        Model::unguard(); // REMOVE NECESSIDADE DE COLOCAR CAMPO NAS MODELS
        Model::preventLazyLoading(! app()->isProduction()); // EVITA MÚLTIPLAS CONSULTAS AO BANCO DE DADOS, SOMENTE EM PRODUÇÃO
    }
}
