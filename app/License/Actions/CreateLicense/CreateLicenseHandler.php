<?php declare(strict_types=1);

namespace App\License\Actions\CreateLicense;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\License\Models\License;
use App\License\Actions\LicenseInformationDto;
use App\License\Actions\CreateLicense\CreateLicense;

final class CreateLicenseHandler
{
    /**
     * Executa a ação
     *
     * @param \App\License\Actions\CreateLicense\CreateLicense $command
     * @return LicenseInformationDto
     */
    public function handle(CreateLicense $command): LicenseInformationDto
    {
        try {
            DB::beginTransaction();
				$license = License::create([
                    'slave_key' => $command->slaveKey,
                    'user_id' => $command->userId,
                    'company_id' => $command->companyId,
                    'company_branch_id' => $command->companyBranchId,
                    'runtime_key_used_to_activate' => $command->runtimeKeyUsedToActivate,
                    'token_used_to_activate' => $command->tokenUsedToActivate,
                    'activated_at' => $command->activatedAt,
                    'deactivated_at' => $command->deactivatedAt
				]);
            DB::commit();
			return LicenseInformationDto::fromModel($license);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
