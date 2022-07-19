<?php declare(strict_types=1);

namespace App\License\Actions\GetLicenseInformation;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\License\Models\License;
use App\License\Actions\LicenseInformationDto;
use App\License\Actions\GetLicenseInformation\GetLicenseInformationCommand;

final class GetLicenseInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\License\Actions\GetLicenseInformation\GetLicenseInformationCommand $command
     * @return \App\License\Actions\LicenseInformationDto
     */
    public function handle(GetLicenseInformationCommand $command): LicenseInformationDto
    {
        try {
            $license = License::where('id', $command->licenseId)->firstOrFail();
			return LicenseInformationDto::fromModel($license);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed license does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
