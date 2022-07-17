<?php declare(strict_types=1);

namespace App\UI\Vehicle\Providers;

use App\Domain\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalizationCommand;
use App\Domain\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalizationHandler;
use App\Domain\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationCommand;
use App\Domain\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationHandler;
use App\Domain\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationListCommand;
use App\Domain\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationListHandler;
use App\Domain\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand;
use App\Domain\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationHandler;
use App\Domain\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationCommand;
use App\Domain\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class VehicleServiceProvider extends ServiceProvider
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
            CreateVehicleLocalizationCommand::class => CreateVehicleLocalizationHandler::class,
            DeleteVehicleLocalizationCommand::class => DeleteVehicleLocalizationHandler::class,			
            GetVehicleLocalizationListCommand::class => GetVehicleLocalizationListHandler::class,
            GetVehicleLocalizationCommand::class => GetVehicleLocalizationHandler::class,
            UpdateVehicleLocalizationCommand::class => UpdateVehicleLocalizationHandler::class,
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
            VehicleCreated::class,
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
			->group(base_path('routes/vehicle/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/vehicle/api.php'));

        require base_path('routes/vehicle/channels.php');
        require base_path('routes/vehicle/console.php');
    }
}
