<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUserById;

use App\Domains\User\Actions\GetUserById\GetUserByIdQuery;
use App\Domains\User\Actions\GetUserById\GetUserByIdCommand;
use App\Domains\User\Actions\GetUserById\GetUserByIdResponse;

final class GetUserByIdHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\GetUserById\GetUserById $query
     * @return \App\Domains\User\Actions\GetUserById\UserDto
     */
    public function handle(GetUserById $command): UserDto
    {
        $user = User::findUserByIdOrFail($query->userId)

        return UserDto::fromModel($user);
    }
}
