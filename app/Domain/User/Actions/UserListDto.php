<?php declare(strict_types=1);

namespace App\Domain\User\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Domain\User\Actions\UserDto;

final class UserListDto
{
    public static function fromCollection(EloquentCollection $users): SupportCollection
    {
        return $users->map(function ($user) {
			return UserDto::fromModel($user);
		});
    }
}
