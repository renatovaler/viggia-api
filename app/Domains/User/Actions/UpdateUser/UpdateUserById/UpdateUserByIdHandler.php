<?php declare(strict_types=1);

namespace App\Domains\User\Actions\UpdateUser\UpdateUserById;

use App\Domains\User\Actions\GetUser\UserDto;
use App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdQuery;
use App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdCommand;

final class UpdateUserByIdHandler
{
    /**
     * @param \App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdQuery
     */
    public $query;

    /**
     * Método construtor da classe
     *
     * @param \App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdQuery $query
     * @return void (implicit)
     */
    public function __construct(UpdateUserByIdQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdCommand $query
     * @return \App\Domains\User\Actions\GetUser\UserDto
     */
    public function handle(UpdateUserByIdCommand $command): UserDto
    {
        $user = $this->query->UpdateUserById($command->id);
        return UserDto::fromModel($user);
    }
}
