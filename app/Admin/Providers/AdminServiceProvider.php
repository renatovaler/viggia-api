<?php declare(strict_types=1);

namespace App\Admin\Providers;

use App\User\Models\User;
use App\Admin\Policies\UserPolicy;

use App\User\Actions\GetUser\GetUser;
use App\User\Actions\GetUser\GetUserHandler;
use App\User\Actions\GetUserList\GetUserList;
use App\User\Actions\GetUserList\GetUserListHandler;
use App\User\Actions\CreateUser\CreateUser;
use App\User\Actions\CreateUser\CreateUserHandler;
use App\User\Actions\DeleteUser\DeleteUser;
use App\User\Actions\DeleteUser\DeleteUserHandler;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformation;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationHandler;


use App\Role\Models\Role;
use App\Admin\Policies\RolePolicy;

use App\Role\Actions\CreateRole\CreateRole;
use App\Role\Actions\CreateRole\CreateRoleHandler;
use App\Role\Actions\DeleteRole\DeleteRole;
use App\Role\Actions\DeleteRole\DeleteRoleHandler;
use App\Role\Actions\UpdateRole\UpdateRole;
use App\Role\Actions\UpdateRole\UpdateRoleHandler;
use App\Role\Actions\GetRoleList\GetRoleList;
use App\Role\Actions\GetRoleList\GetRoleListHandler;
use App\Role\Actions\GetRoleInformation\GetRoleInformation;
use App\Role\Actions\GetRoleInformation\GetRoleInformationHandler;

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
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
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
        $this->registersAndHandlers();

        // events
        $this->registerEventsAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registersAndHandlers(): void
    {
        Bus::map([
            // User actions
            CreateUser::class => CreateUserHandler::class,
            DeleteUser::class => DeleteUserHandler::class,
            GetUser::class => GetUserHandler::class,
            GetUserList::class => GetUserListHandler::class,
            UpdateUserPersonalInformation::class => UpdateUserPersonalInformationHandler::class,

            // Role actions
            CreateRole::class => CreateRoleHandler::class,
            DeleteRole::class => DeleteRoleHandler::class,
            UpdateRole::class => UpdateRoleHandler::class,
            GetRoleList::class => GetRoleListHandler::class,
            GetRoleInformation::class => GetRoleInformationHandler::class,
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
