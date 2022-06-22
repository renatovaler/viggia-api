<?php declare(strict_types=1);

namespace App\Domain\Role\Actions;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Actions\RoleInformationDto;

final class RoleInformationListDto
{
    public static function fromCollection(Role $roles): self
    {
        return $roles->map(function ($role) {
			return RoleInformationDto::fromModel($role);
		});
    }
}
