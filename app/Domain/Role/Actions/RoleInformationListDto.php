<?php declare(strict_types=1);

namespace App\Domain\Role\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Domain\Role\Actions\RoleInformationDto;

final class RoleInformationListDto
{
    public static function fromCollection(EloquentCollection $roles): SupportCollection
    {
        return $roles->map(function ($role) {
			return RoleInformationDto::fromModel($role);
		});
    }
}
