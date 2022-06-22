<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUserPassword;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\User\Models\User;
use App\Domain\User\Actions\UserDto;
use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;

final class UpdateUserPasswordHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand $command
     * @return \App\Domain\User\Actions\UserDto
     */
    public function handle(UpdateUserPasswordCommand $command): UserDto
    {
        try {
            DB::beginTransaction();
				$user = User::findUserByIdOrFail($command->id);
                $user->forceFill([
                    'password' => Hash::make($command->password),
                    'password_changed_at' => now()
                ])->save();
            DB::commit();
			return UserDto::fromModel($user);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
