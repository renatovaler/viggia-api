<?php declare(strict_types=1);

namespace App\Domain\License\Actions\GetLicenseList;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\License\Models\License;
use App\Domain\License\Actions\LicenseInformationListDto;

final class GetLicenseListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
        try {
            $licenses = License::all();
            return LicenseInformationListDto::fromCollection($licenses);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
