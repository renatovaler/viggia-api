<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUser\GetUserByEmail;

use App\Domains\User\Models\User;

final class GetUserByEmailQuery
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
     * Busca o usuário através do e-mail ou dispara uma exceção
     *
     * @param readonly string $email
     * @return \App\Domains\User\Models\User
     */
	public function getUserByEmail(string $email): User
	{
		return $this->model->findUserByEmailOrFail($email);
	}
}
