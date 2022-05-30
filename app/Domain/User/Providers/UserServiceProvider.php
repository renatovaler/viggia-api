<?php

namespace App\Domain\User\Providers;

use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand;
use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdHandler;
use App\Domain\User\Actions\GetUser\GetUserByEmail\GetUserByEmailCommand;
use App\Domain\User\Actions\GetUser\GetUserByEmail\GetUserByEmailHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Domain\User\Model' => 'App\Domain\User\Policies\UserPolicy',
    ];

    /**
     * Inicia os serviços
     *
     * @return void
     */
    public function boot(): void
    {
        // register policies
        $this->registerPolicies();

        // map routes
        $this->map();

        // commands and handlers
        $this->registerCommandsAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registerCommandsAndHandlers(): void
    {
        Bus::map([
            GetUserByIdCommand::class => GetUserByIdHandler::class,
            GetUserByEmailCommand::class => GetUserByEmailHandler::class,
        ]);
    }

    /**
     * Registra os serviços utilizados por esse provedor de serviços.
     *
     * @return void
     */
    public function register(): void {}

    /**
     * Define as rotas do provedor de serviços.
     *
     * @return void
     */
    public function map(): void
    {
        Route::middleware(['web'])
			->group(base_path('routes/user/web.php'));

        Route::middleware(['api'])
            ->prefix('api')
            ->group(base_path('routes/user/api.php'));

        require base_path('routes/user/channels.php');
        require base_path('routes/user/console.php');
    }
}
