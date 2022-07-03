<?php declare(strict_types=1);

namespace App\Domain\License\Actions\DeleteLicense;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Domain\License\Models\License;
use App\Domain\License\Actions\DeleteLicense\DeleteLicenseCommand;

final class DeleteLicenseHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\License\Actions\DeleteLicense\DeleteLicenseCommand $command
     * @return void
     */
    public function handle(DeleteLicenseCommand $command): void
    {
        try {
            DB::beginTransaction();
				License::removeLicense($command->licenseId);
            DB::commit();
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
