<?php declare(strict_types=1);

namespace App\Company\Actions\DeleteCompany;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Company\Models\Company;
use App\Company\Actions\DeleteCompany\DeleteCompany;

final class DeleteCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\DeleteCompany\DeleteCompany $command
     * @return void
     */
    public function handle(DeleteCompany $command): void
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
