<?php declare(strict_types=1);

namespace App\Domain\License\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Domain\License\Actions\LicenseInformationDto;

final class LicenseInformationListDto
{
    public static function fromCollection(EloquentCollection $licenses): SupportCollection
    {
        return $licenses->map(function ($license) {
			return LicenseInformationDto::fromModel($license);
		});
    }
}
