<?php

namespace App\Kernel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Indica ao Laravel que o carregamento desse provedor NÃO deve ser adiado.
     */
    protected $defer = false;

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
		// Corrige o tamanho padrão da string no mysql para evitar erros
        Schema::defaultStringLength(191);

        // Define um "pattern" numérico global para o parâmetro "ID" de todas as rotas.
        Route::pattern('id', '[0-9]+');
    }
}
