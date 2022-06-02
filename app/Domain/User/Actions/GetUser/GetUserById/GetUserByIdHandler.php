<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetUser\GetUserById;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand;

final class GetUserByIdHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(GetUserByIdCommand $command): UserDto
    {
        $user = User::findUserByIdOrFail($command->id);
        return UserDto::fromModel($user);
    }
}
