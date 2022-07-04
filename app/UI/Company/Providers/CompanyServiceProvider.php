<?php declare(strict_types=1);

namespace App\UI\Company\Providers;

// Company
use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;
use App\Domain\Company\Actions\GetCompany\GetCompanyHandler;
use App\Domain\Company\Actions\GetCompanyList\GetCompanyListCommand;
use App\Domain\Company\Actions\GetCompanyList\GetCompanyListHandler;
use App\Domain\Company\Actions\GetMyselfCompanyList\GetMyselfCompanyListCommand;
use App\Domain\Company\Actions\GetMyselfCompanyList\GetMyselfCompanyListHandler;

use App\Domain\Company\Actions\SwitchCompany\SwitchCompanyCommand;
use App\Domain\Company\Actions\SwitchCompany\SwitchCompanyHandler;

use App\Domain\Company\Actions\CreateCompany\CreateCompanyCommand;
use App\Domain\Company\Actions\CreateCompany\CreateCompanyHandler;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationHandler;

use App\Domain\Company\Actions\AddCompanyMember\AddCompanyMemberCommand;
use App\Domain\Company\Actions\AddCompanyMember\AddCompanyMemberHandler;
use App\Domain\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberCommand;
use App\Domain\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberHandler;

// Company Branch
use App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchnCommand;
use App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchHandler;
use App\Domain\Company\Actions\CreateCompanyBranch\CreateCompanyBranchCommand;
use App\Domain\Company\Actions\CreateCompanyBranch\CreateCompanyBranchHandler;
use App\Domain\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMemberCommand;
use App\Domain\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMemberHandler;
use App\Domain\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberCommand;
use App\Domain\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberHandler;
use App\Domain\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationCommand;
use App\Domain\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationHandler;

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
        'App\Domain\Company\Models\CompanyBranch' => 'App\Domain\Company\Policies\CompanyBranchPolicy',
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
            GetMyselfCompanyListCommand::class => GetMyselfCompanyListHandler::class,
            GetCompanyListCommand::class => GetCompanyListHandler::class,
            GetCompanyCommand::class => GetCompanyHandler::class,
            CreateCompanyCommand::class => CreateCompanyHandler::class,
            AddCompanyMemberCommand::class => AddCompanyMemberHandler::class,
            RemoveCompanyMemberCommand::class => RemoveCompanyMemberHandler::class,
            UpdateCompanyInformationCommand::class => UpdateCompanyInformationHandler::class,

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
