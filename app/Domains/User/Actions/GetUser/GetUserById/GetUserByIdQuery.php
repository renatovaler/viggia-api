<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUser\GetUserById;

use App\Domains\User\Models\User;

final class GetUserByIdQuery
{
    public $model;
    /**
     * Método construtor da classe
     *
     * @param \App\Domains\User\Models\User $user
     * @return void (implicit)
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Busca o usuário através do id ou dispara uma exceção
     *
     * @param readonly int $id
     * @return \App\Domains\User\Models\User
     */
	public function getUserById(int $id): User
	{
		return $this->model->findUserByIdOrFail($id);
	}
}
