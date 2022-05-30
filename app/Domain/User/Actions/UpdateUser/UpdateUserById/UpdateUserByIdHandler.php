<?php declare(strict_types=1);

namespace App\Domains\User\Actions\UpdateUser\UpdateUserById;

use App\Domains\User\Models\User;
use App\Domains\User\Actions\GetUser\UserDto;
use App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdCommand;

final class UpdateUserByIdHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domains\User\Actions\UpdateUser\UpdateUserById\UpdateUserByIdCommand $query
     * @return \App\Domains\User\Actions\GetUser\UserDto
     */
    public function handle(UpdateUserByIdCommand $command): UserDto
    {
        try {
            DB::beginTransaction();
				//$user
            DB::commit();			
			return UserDto::fromModel($user);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 403);
        }
    }
}
