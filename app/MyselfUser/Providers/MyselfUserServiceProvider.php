<?php declare(strict_types=1);

namespace App\MyselfUser\Providers;

use App\User\Actions\GetUser\GetUserCommand;
use App\User\Actions\GetUser\GetUserHandler;

use App\User\Actions\GetUser\GetUserByEmail\GetUserByEmailCommand;
use App\User\Actions\GetUser\GetUserByEmail\GetUserByEmailHandler;

use App\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;
use App\User\Actions\UpdateUserPassword\UpdateUserPasswordHandler;

use App\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoCommand;
use App\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoHandler;

use App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhotoCommand;
use App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhotoHandler;

use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationHandler;

//use Illuminate\Support\Facades\Event;
//use App\User\Events\UserCreated;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class MyselfUserServiceProvider extends ServiceProvider
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
    protected $policies = [];

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
            GetUserCommand::class => GetUserHandler::class,
            GetUserByEmailCommand::class => GetUserByEmailHandler::class,
            UpdateUserPasswordCommand::class => UpdateUserPasswordHandler::class,
            UpdateUserPersonalInformationCommand::class => UpdateUserPersonalInformationHandler::class,
            UpdateUserProfilePhotoCommand::class => UpdateUserProfilePhotoHandler::class,
            DeleteUserProfilePhotoCommand::class => DeleteUserProfilePhotoHandler::class
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
			->group(base_path('routes/myself-user/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/myself-user/api.php'));

        require base_path('routes/myself-user/channels.php');
        require base_path('routes/myself-user/console.php');
    }
}
