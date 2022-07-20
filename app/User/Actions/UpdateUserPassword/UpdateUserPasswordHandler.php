<?php declare(strict_types=1);

namespace App\User\Actions\UpdateUserPassword;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User\Models\User;
use App\User\Actions\UserDto;
use App\User\Actions\UpdateUserPassword\UpdateUserPassword;

final class UpdateUserPasswordHandler
{
    /**
     * Executa a ação
     *
     * @param \App\User\Actions\UpdateUserPassword\UpdateUserPassword $command
     * @return \App\User\Actions\UserDto
     */
    public function handle(UpdateUserPassword $command): UserDto
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
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed user does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
