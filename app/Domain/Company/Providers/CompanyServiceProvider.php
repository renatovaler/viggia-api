<?php

namespace App\Domain\Company\Providers;

use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;
use App\Domain\Company\Actions\GetCompany\GetCompanyHandler;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
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
        'App\Domain\Company\Models\Company' => 'App\Domain\Company\Policies\CompanyPolicy',
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
            GetCompanyCommand::class => GetCompanyHandler::class,
            UpdateCompanyInformationCommand::class => UpdateCompanyInformationHandler::class,
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
			->group(base_path('routes/company/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/company/api.php'));

        require base_path('routes/company/channels.php');
        require base_path('routes/company/console.php');
    }
}
