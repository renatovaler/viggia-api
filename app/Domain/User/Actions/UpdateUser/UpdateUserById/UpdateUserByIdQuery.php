<?php declare(strict_types=1);

namespace App\Domains\User\Actions\UpdateUser\UpdateUserById;


use App\Domains\User\Models\User;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateUserByIdQuery
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
	public function UpdateUserById(
        int $id,
        string $name,
        string $email,
        Carbon $emailVerifiedAt
	): User
	{
        try {
            DB::beginTransaction();
				$user = $this->model->find
            DB::commit();			
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 403);
        }
	}
}
