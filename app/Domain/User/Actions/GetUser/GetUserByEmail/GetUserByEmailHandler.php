<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetUser\GetUserByEmail;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\GetUser\GetUserByEmail\GetUserByEmailCommand;

final class GetUserByEmailHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\GetUser\GetUserByEmailCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(GetUserByEmailCommand $command): UserDto
    {
        $user = User::findUserByEmailOrFail($command->email);
        return UserDto::fromModel($user);
    }
}
