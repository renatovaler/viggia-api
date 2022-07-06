<?php declare(strict_types=1);

namespace App\Domain\License\Actions\UpdateLicense;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domain\License\Models\License;
use App\Domain\License\Actions\LicenseInformationDto;
use App\Domain\License\Actions\UpdateLicense\UpdateLicenseCommand;

final class UpdateLicenseHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\License\Actions\UpdateLicense\UpdateLicenseCommand $command
     * @return \App\Domain\License\Actions\LicenseInformationDto
     */
    public function handle(UpdateLicenseCommand $command): LicenseInformationDto
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
