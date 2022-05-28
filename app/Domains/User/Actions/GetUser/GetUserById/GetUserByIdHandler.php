<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUser\GetUserById;

use App\Domains\User\Actions\GetUser\UserDto;
use App\Domains\User\Actions\GetUser\GetUserById\GetUserByIdQuery;
use App\Domains\User\Actions\GetUser\GetUserById\GetUserByIdCommand;

final class GetUserByIdHandler
{
    /**
     * @param \App\Domains\User\Actions\GetUser\GetUserById\GetUserByIdQuery
     */
    public $query;

    /**
     * Método construtor da classe
     *
     * @param \App\Domains\User\Actions\GetUser\GetUserById\GetUserByIdQuery $query
     * @return void (implicit)
     */
    public function __construct(GetUserByIdQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\GetUser\GetUserById\GetUserByIdCommand $query
     * @return \App\Domains\User\Actions\GetUser\UserDto
     */
    public function handle(GetUserByIdCommand $command): UserDto
    {
        $user = $this->query->getUserById($command->id);
        return UserDto::fromModel($user);
    }
}
