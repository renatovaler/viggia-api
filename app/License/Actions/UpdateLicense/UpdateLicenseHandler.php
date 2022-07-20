<?php declare(strict_types=1);

namespace App\License\Actions\UpdateLicense;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\License\Models\License;
use App\License\Actions\LicenseInformationDto;
use App\License\Actions\UpdateLicense\UpdateLicense;

final class UpdateLicenseHandler
{
    /**
     * Executa a ação
     *
     * @param \App\License\Actions\UpdateLicense\UpdateLicense $command
     * @return \App\License\Actions\LicenseInformationDto
     */
    public function handle(UpdateLicense $command): LicenseInformationDto
    {
        try {
            DB::beginTransaction();
				$license = License::where('id', $command->licenseId)->firstOrFail();
                $license->forceFill([
                    'slave_key' => $command->slaveKey,
                    'user_id' => $command->userId,
                    'company_id' => $command->companyId,
                    'company_branch_id' => $command->companyBranchId,
                    'runtime_key_used_to_activate' => $command->runtimeKeyUsedToActivate,
                    'token_used_to_activate' => $command->tokenUsedToActivate,
                    'activated_at' => $command->activatedAt,
                    'deactivated_at' => $command->deactivatedAt
                ])->save();
            DB::commit();
			return LicenseInformationDto::fromModel($license);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
