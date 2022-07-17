<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\DeleteCompany;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\DeleteCompany\DeleteCompanyCommand;

final class DeleteCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\DeleteCompany\DeleteCompanyCommand $command
     * @return void
     */
    public function handle(DeleteCompanyCommand $command): void
    {
        try {
            DB::beginTransaction();
				(new Company())->deleteCompany($command->id);
            DB::commit();
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), (int) $e->getCode() );
        }
    }
}
