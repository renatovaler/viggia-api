<?php declare(strict_types=1);

namespace App\User\Actions\GetUser;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User\Models\User;
use App\User\Actions\UserDto;
use App\User\Actions\GetUser\GetUser;

final class GetUserHandler
{
    /**
     * Executa a ação
     *
     * @param \App\User\Actions\GetUser\GetUser $command
     * @return \App\User\Actions\UserDto
     */
    public function handle(GetUser $command): UserDto
    {
        $user = User::findUserByIdOrFail($command->id);
        return UserDto::fromModel($user);
    }
}
