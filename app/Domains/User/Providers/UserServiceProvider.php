<?php

namespace App\Domains\User\Providers;

use App\Domains\User\Actions\GetUserProfileInformation;
use App\Domains\User\Contracts\iGetUserProfileInformation;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indica ao Laravel que o carregamento desse provedor NÃO deve ser adiado.
     */
    protected $defer = false;

    /**
     * Namespace aplicado às rotas de autenticação.
     *
     * Definido como o namespace raiz do gerador de URL dentro das rotas.
     *
     * @var string
     */
    protected $authNamespace= 'App\Domains\User\Auth\Http\Controllers';

    /**
     * Namespace aplicado às rotas de usuários "administradores".
     *
     * Definido como o namespace raiz do gerador de URL dentro das rotas.
     *
     * @var string
     */
    protected $adminNamespace= 'App\Domains\User\Admin\Http\Controllers';

    /**
     * Namespace aplicado às rotas de usuários "comuns".
     *
     * Definido como o namespace raiz do gerador de URL dentro das rotas.
     *
     * @var string
     */
    protected $commonNamespace= 'App\Domains\User\Common\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/user/profile';

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Domains\User\Model' => 'App\Domains\User\Policies\UserPolicy',
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
		// Auth routes
        Route::middleware(['web'])
            ->prefix('users/auth')
            ->namespace($this->authNamespace)
			->group(base_path('routes/web/user/auth.php'));

        Route::middleware(['api'])
            ->prefix('api/users/auth')
            ->namespace($this->authNamespace)
            ->group(base_path('routes/api/user/auth.php'));

		// Admin routes
        Route::middleware(['web'])
            ->prefix('admin/users')
             ->namespace($this->adminNamespace)
			->group(base_path('routes/web/user/admin.php'));

        Route::middleware(['api'])
            ->prefix('api/admin/users')
			->namespace($this->adminNamespace)
			->group(base_path('routes/api/user/admin.php'));

		// Common routes
        Route::middleware(['web'])
            ->prefix('users')
             ->namespace($this->commonNamespace)
			->group(base_path('routes/web/user/common.php'));

        Route::middleware(['api'])
            ->prefix('api/users')
			->namespace($this->commonNamespace)
			->group(base_path('routes/api/user/common.php'));

    }
}
