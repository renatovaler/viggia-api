<?php declare(strict_types=1);

namespace App\User\Providers;

use App\User\Actions\GetUser\GetUser;
use App\User\Actions\GetUser\GetUserHandler;

use App\User\Actions\UpdateUserPassword\UpdateUserPassword;
use App\User\Actions\UpdateUserPassword\UpdateUserPasswordHandler;

use App\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhoto;
use App\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoHandler;

use App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhoto;
use App\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhotoHandler;

use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformation;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationHandler;

//use Illuminate\Support\Facades\Event;
//use App\User\Events\UserCreated;

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
            GetUser::class => GetUserHandler::class,
            UpdateUserPassword::class => UpdateUserPasswordHandler::class,
            UpdateUserPersonalInformation::class => UpdateUserPersonalInformationHandler::class,
            UpdateUserProfilePhoto::class => UpdateUserProfilePhotoHandler::class,
            DeleteUserProfilePhoto::class => DeleteUserProfilePhotoHandler::class
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
        /*
        *   Myself routes
        *   São rotas do "usuário logado"
        */
        Route::middleware(['web'])
			->group(base_path('routes/myself/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/myself/api.php'));

        require base_path('routes/myself/channels.php');
        require base_path('routes/myself/console.php');
    }
}
