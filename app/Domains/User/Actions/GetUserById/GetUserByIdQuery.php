<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUserById;

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
	
	public function getUserById(int $id): User
	{
		return $this->model->getUserById($id);
	}
}
