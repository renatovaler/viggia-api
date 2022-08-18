<?php declare(strict_types=1);

namespace App\Company\Providers;

// Company
use App\Company\Actions\DeleteCompany\DeleteCompany;
use App\Company\Actions\DeleteCompany\DeleteCompanyHandler;
use App\Company\Actions\GetCompany\GetCompany;
use App\Company\Actions\GetCompany\GetCompanyHandler;
use App\Company\Actions\GetCompanyList\GetCompanyList;
use App\Company\Actions\GetCompanyList\GetCompanyListHandler;
use App\Company\Actions\GetCurrentUserCompanyList\GetCurrentUserCompanyList;
use App\Company\Actions\GetCurrentUserCompanyList\GetCurrentUserCompanyListHandler;

use App\Company\Actions\SwitchCompany\SwitchCompany;
use App\Company\Actions\SwitchCompany\SwitchCompanyHandler;

use App\Company\Actions\CreateCompany\CreateCompany;
use App\Company\Actions\CreateCompany\CreateCompanyHandler;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformation;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationHandler;

// Invite Company Member
use App\Company\Actions\InviteNewCompanyMember\InviteNewCompanyMember;
use App\Company\Actions\InviteNewCompanyMember\InviteNewCompanyMemberHandler;
use App\Company\Actions\DeleteInviteToBeCompanyMember\DeleteInviteToBeCompanyMember;
use App\Company\Actions\DeleteInviteToBeCompanyMember\DeleteInviteToBeCompanyMemberHandler;

// Add/Remove Company Member
use App\Company\Actions\AddCompanyMember\AddCompanyMember;
use App\Company\Actions\AddCompanyMember\AddCompanyMemberHandler;
use App\Company\Actions\RemoveCompanyMember\RemoveCompanyMember;
use App\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberHandler;

// Company Branch
use App\Company\Actions\GetCompanyBranch\GetCompanyBranchn;
use App\Company\Actions\GetCompanyBranch\GetCompanyBranchHandler;
use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranch;
use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranchHandler;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMember;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMemberHandler;
use App\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMember;
use App\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberHandler;
use App\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformation;
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
        $this->registersAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registersAndHandlers(): void
    {
        Bus::map([
            // Company
            CreateCompany::class => CreateCompanyHandler::class,
            UpdateCompanyInformation::class => UpdateCompanyInformationHandler::class,
            DeleteCompany::class => DeleteCompanyHandler::class,
            GetCompany::class => GetCompanyHandler::class,
            GetCurrentUserCompanyList::class => GetCurrentUserCompanyListHandler::class,
            GetCompanyList::class => GetCompanyListHandler::class,
            InviteNewCompanyMember::class => InviteNewCompanyMemberHandler::class,
            DeleteInviteToBeCompanyMember::class => DeleteInviteToBeCompanyMemberHandler::class,
            AddCompanyMember::class => AddCompanyMemberHandler::class,
            RemoveCompanyMember::class => RemoveCompanyMemberHandler::class,

            SwitchCompany::class => SwitchCompanyHandler::class,

            // CompanyBranch
            GetCompanyBranchn::class => GetCompanyBranchHandler::class,
            CreateCompanyBranch::class => CreateCompanyBranchHandler::class,
            AddCompanyBranchMember::class => AddCompanyBranchMemberHandler::class,
            RemoveCompanyBranchMember::class => RemoveCompanyBranchMemberHandler::class,
            UpdateCompanyBranchInformation::class => UpdateCompanyBranchInformationHandler::class,
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
