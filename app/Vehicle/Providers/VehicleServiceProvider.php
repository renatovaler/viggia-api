<?php declare(strict_types=1);

namespace App\Vehicle\Providers;

use App\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalization;
use App\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalizationHandler;
use App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalization;
use App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationHandler;
use App\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationList;
use App\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationListHandler;
use App\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalization;
use App\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationHandler;
use App\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalization;
use App\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationHandler;

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
            CreateVehicleLocalization::class => CreateVehicleLocalizationHandler::class,
            DeleteVehicleLocalization::class => DeleteVehicleLocalizationHandler::class,			
            GetVehicleLocalizationList::class => GetVehicleLocalizationListHandler::class,
            GetVehicleLocalization::class => GetVehicleLocalizationHandler::class,
            UpdateVehicleLocalization::class => UpdateVehicleLocalizationHandler::class,
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
