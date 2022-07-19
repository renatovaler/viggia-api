<?php declare(strict_types=1);

namespace App\Company\Providers;

// Company
use App\Company\Actions\DeleteCompany\DeleteCompanyCommand;
use App\Company\Actions\DeleteCompany\DeleteCompanyHandler;
use App\Company\Actions\GetCompany\GetCompanyCommand;
use App\Company\Actions\GetCompany\GetCompanyHandler;
use App\Company\Actions\GetCompanyList\GetCompanyListCommand;
use App\Company\Actions\GetCompanyList\GetCompanyListHandler;
use App\Company\Actions\GetCurrentUserCompanyList\GetCurrentUserCompanyListCommand;
use App\Company\Actions\GetCurrentUserCompanyList\GetCurrentUserCompanyListHandler;

use App\Company\Actions\SwitchCompany\SwitchCompanyCommand;
use App\Company\Actions\SwitchCompany\SwitchCompanyHandler;

use App\Company\Actions\CreateCompany\CreateCompanyCommand;
use App\Company\Actions\CreateCompany\CreateCompanyHandler;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationHandler;

use App\Company\Actions\AddCompanyMember\AddCompanyMemberCommand;
use App\Company\Actions\AddCompanyMember\AddCompanyMemberHandler;
use App\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberCommand;
use App\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberHandler;

// Company Branch
use App\Company\Actions\GetCompanyBranch\GetCompanyBranchnCommand;
use App\Company\Actions\GetCompanyBranch\GetCompanyBranchHandler;
use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranchCommand;
use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranchHandler;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMemberCommand;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMemberHandler;
use App\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberCommand;
use App\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberHandler;
use App\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationCommand;
use App\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Company\Models\Company;
use App\Company\Policies\CompanyPolicy;

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
        Company::class => CompanyPolicy::class,
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
            // Company
            CreateCompanyCommand::class => CreateCompanyHandler::class,
            UpdateCompanyInformationCommand::class => UpdateCompanyInformationHandler::class,
            DeleteCompanyCommand::class => DeleteCompanyHandler::class,
            GetCompanyCommand::class => GetCompanyHandler::class,
            GetCurrentUserCompanyListCommand::class => GetCurrentUserCompanyListHandler::class,
            GetCompanyListCommand::class => GetCompanyListHandler::class,
            AddCompanyMemberCommand::class => AddCompanyMemberHandler::class,
            RemoveCompanyMemberCommand::class => RemoveCompanyMemberHandler::class,

            SwitchCompanyCommand::class => SwitchCompanyHandler::class,

            // CompanyBranch
            GetCompanyBranchnCommand::class => GetCompanyBranchHandler::class,
            CreateCompanyBranchCommand::class => CreateCompanyBranchHandler::class,
            AddCompanyBranchMemberCommand::class => AddCompanyBranchMemberHandler::class,
            RemoveCompanyBranchMemberCommand::class => RemoveCompanyBranchMemberHandler::class,
            UpdateCompanyBranchInformationCommand::class => UpdateCompanyBranchInformationHandler::class,
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
