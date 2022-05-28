<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUser\GetUserByEmail;

use App\Domains\User\Actions\GetUser\UserDto;
use App\Domains\User\Actions\GetUser\GetUserByEmailQuery;
use App\Domains\User\Actions\GetUser\GetUserByEmailCommand;

final class GetUserByEmailHandler
{
    /**
     * @param \App\Domains\User\Actions\GetUser\GetUserByEmailQuery
     */
    public $query;

    /**
     * Método construtor da classe
     *
     * @param \App\Domains\User\Actions\GetUser\GetUserByEmailQuery $query
     * @return void (implicit)
     */
    public function __construct(GetUserByEmailQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\GetUser\GetUserByEmailCommand $query
     * @return \App\Domains\User\Actions\GetUser\UserDto
     */
    public function handle(GetUserByEmailCommand $command): UserDto
    {
        $user = $this->query->GetUserByEmail($command->email);
        return UserDto::fromModel($user);
    }
}
