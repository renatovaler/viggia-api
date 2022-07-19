<?php declare(strict_types=1);

namespace App\Admin\Providers;

use App\User\Actions\GetUserList\GetUserListCommand;
use App\User\Actions\GetUserList\GetUserListHandler;
use App\User\Actions\GetUser\GetUserCommand;
use App\User\Actions\GetUser\GetUserHandler;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AdminServiceProvider extends ServiceProvider
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
        //'App\User\Models\User' => 'App\Admin\Policies\UserPolicy',
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

        // events
        $this->registerEventsAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registerCommandsAndHandlers(): void
    {
        Bus::map([
            GetUserListCommand::class => GetUserListHandler::class,
            GetUserCommand::class => GetUserHandler::class,
            UpdateUserPersonalInformationCommand::class => UpdateUserPersonalInformationHandler::class,
        ]);
    }


    /**
     * Mapeia os eventos e handlers respectivos
     *
     * @return void
     */
    public function registerEventsAndHandlers(): void
    {
        /*
        Event::listen(
            UserCreated::class,
            [CreatePersonalCompany::class, 'handle']
        );
        */
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
			->prefix('admin')
			->group(base_path('routes/admin/web.php'));

        Route::middleware(['api'])
			->prefix('admin')
            ->group(base_path('routes/admin/api.php'));

        require base_path('routes/admin/channels.php');
        require base_path('routes/admin/console.php');
    }
}
