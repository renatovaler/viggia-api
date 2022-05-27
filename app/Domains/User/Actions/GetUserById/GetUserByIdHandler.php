<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUserById;

use App\Domains\User\Actions\GetUserById\GetUserByIdQuery;
use App\Domains\User\Actions\GetUserById\GetUserByIdCommand;
use App\Domains\User\Actions\GetUserById\GetUserByIdResponse;

final class GetUserByIdHandler
{

    public $query;
    /**
     * Método construtor da classe
     *
     * @param \App\Domains\User\Actions\GetUserById\GetUserByIdQuery $query
     * @return void (implicit)
     */
    public function __construct(GetUserByIdQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\GetUserById\GetUserByIdCommand $command
     * @return \App\Domains\User\Actions\GetUserById\GetUserByIdResponse
     */
    public function handle(GetUserByIdCommand $command): GetUserByIdResponse
    {
        $user = $this->query->getUserById($command->id);
        return (
            new GetUserByIdResponse(
                $user->id,
                $user->name,
                $user->email,
                $user->email_verified_at
            )
        );
    }
}
